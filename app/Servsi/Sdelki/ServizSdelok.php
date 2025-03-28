<?php
 

namespace App\Servsi\Sdelki;

use App\Common\Exceptions\ZapreschenoIskluchenie;
use App\Common\Primesi\ToJson;
use App\Models\Sdelki;
use App\Khranilischa\BonusiKlientov\BonusBalanceException;
use App\Khranilischa\BonusiKlientov\BonusRepo;
use App\Khranilischa\BonusiKlientov\Po\BonusPo;
use App\Khranilischa\Klienti\KlientNeNaidenIskluchenie;
use App\Khranilischa\Klienti\CustomersRepository;
use App\Khranilischa\Sdelki\ProstieObjiecti\IzmenitStatusSdelkiNaProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\DostavkaProstoObjiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\ObnovitSdelkuProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\OtmenitSdelkuProsoObjiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\SozdatZakazProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\FiltrZakazovProsto;
use App\Khranilischa\Sdelki\ProstieObjiecti\StatusZakazaProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\SdelkiNaKurieraProstoObjiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\ProstoiObiectSortirovkiZakaza;
use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Khranilischa\Sdelki\Perechislenia\OrderType1;
use App\Khranilischa\Sdelki\Perechislenia\OrderType2;
use App\Khranilischa\Sdelki\ZakazaNeNaidenIskluchenie;
use App\Khranilischa\Sdelki\InyterfaceRepositoriaSdelok;
use App\Khranilischa\Nastroiki\AdminSettingsRepository;
use App\Servsi\Authentication\ZaloginenniPolzovatel;
use Illuminate\Support\Facades\DB;
use Psr\Log\LogLevel;
use Throwable;

class ServizSdelok
{
    use ToJson;

    const OrdersPerPage = 50;

    public function __construct(
        private readonly InyterfaceRepositoriaSdelok $orderRepository,
        private readonly AdminSettingsRepository     $adminSettingsRepository,
        private readonly CustomersRepository         $customersRepository,
        private readonly BonusRepo                   $bonusRepository,
    )
    {
    }


    public function poluchitNomertelephonaVladelcaZakaza(int $order_id)
    {
        return $this->orderRepository->poluchitNomerTelephona($order_id);
    }

    /**
     * @throws ZakazaNeNaidenIskluchenie
     */
    public function getAll(
        ZaloginenniPolzovatel          $user,
        ?ProstoiObiectSortirovkiZakaza $sortingOpts = null,
        ?FiltrZakazovProsto            $filters = null,
        StatusiSdelok                  $orderStatus = null
    )
    {
        if ($user->isAdmin()) {
            $customerFilters = FiltrZakazovProsto::create();
            $customerFilters->customer_id = $user->id;
            $customerFilters->order_statuses = $filters->order_statuses;
            return $this->orderRepository->poluchitOdinZakaz(0, self::OrdersPerPage, $sortingOpts, $customerFilters, $orderStatus);
        }

        return $this->orderRepository->poluchitOdinZakaz(0, self::OrdersPerPage, $sortingOpts, $filters, $orderStatus);
    }


    /**
     * @throws KlientNeNaidenIskluchenie
     * @throws BonusBalanceException|Throwable
     */
    public function izmenitStatusSdelki(
        StatusZakazaProstoObiect $orderStatusDto, DostavkaProstoObjiect $incomingOrderDto,
        ZaloginenniPolzovatel    $user, ?BonusRepo $bonusRepository = null
    ): ?DostavkaProstoObjiect
    {
        $this->orderStatusChangeRules($orderStatusDto, $incomingOrderDto);

        $orderStatusDto->setCreatorId($user->id);
        $orderStatus = $orderStatusDto->getStatusCode();

        if (StatusiSdelok::PolucheniKurierom === $orderStatus) {
            $incomingOrderDto->setReceivedAt(now());
        }

        if (in_array($orderStatus, [StatusiSdelok::Vidan, StatusiSdelok::VidanOplachenChastichno])) {
            $incomingOrderDto->setIssuedAt(now());
        }

        if (!$this->oplachenLiZakazPolnostiu($orderStatusDto, $incomingOrderDto)) {
            $orderStatus = StatusiSdelok::VidanOplachenChastichno;
        }

        try {
            DB::beginTransaction();

            $customer = $this->customersRepository->naitiKlientaPoIdentificatoru($incomingOrderDto->getCustomerId());

            if (in_array($orderStatus, [
                StatusiSdelok::Vidan,
                StatusiSdelok::VidanOplachenChastichno
            ])) {
                if (($amount_paid_bonuses = $orderStatusDto->getAmountPaidBonus()) > 0) {

                    $bonusRepository?->withdrawBonus(new BonusPo(
                        bonus_amount: -$amount_paid_bonuses,
                        order_id: $orderStatusDto->getOrderId(),
                        employee_id: $user->id,
                    ));
                }
            }


            if ($orderStatus === StatusiSdelok::Vidan) {

                $debt = $this->customersRepository->poluchitDolgKlienta($incomingOrderDto->getCustomerId());

                if (0 === $debt) {

                    $bonus_percent = $customer->isVip() ?
                        $this->adminSettingsRepository->getVipBonus() :
                        $this->adminSettingsRepository->getStandardBonus();

                    $bonus_amount = $incomingOrderDto->getPrice() * ($bonus_percent / 100); // %

                    $bonus = new BonusPo(
                        bonus_amount: $bonus_amount,
                        order_id: $orderStatusDto->getOrderId(),
                        employee_id: $user->id,
                        comment: '',
                    );

                    $bonusRepository?->addBonus($bonus);
                }
            }

            $newStatus = $this->orderRepository->izmenitStatusZakaza($orderStatusDto, $incomingOrderDto);
            DB::commit();

            return $newStatus;
        } catch (Throwable $e) {
            DB::rollBack();
            \Log::log(LogLevel::DEBUG, $e);
            throw $e;
        }

    }


    public
    function updateBarcodeImage(DostavkaProstoObjiect $incomingOrder)
    {
        return $this->orderRepository->obnovitShtrikhKodSdelki($incomingOrder);
    }

    /**
     * @throws ZapreschenoIskluchenie
     */
    public function getIncomingById(int $order_id, ZaloginenniPolzovatel $user)
    {
        if ($user->isCustomer() && !$this->orderRepository->prinadlejitLiZakazPokupateliu($user->id, $order_id)) {
            throw new ZapreschenoIskluchenie("Not allowed");
        }

        $io = $this->orderRepository->poluchitSdelku($order_id, user_id: $user->isCustomer() ? $user->id : null);

        return $io->toArray();
    }


    /**
     * @throws ZapreschenoIskluchenie
     */
    public function cancelOrder(OtmenitSdelkuProsoObjiect $dto, ZaloginenniPolzovatel $user)
    {
        if (!$user->isCustomer()) {
            throw new ZapreschenoIskluchenie("");
        }

        $order = $this->orderRepository->poluchitSdelkuPoIdentificatoru($dto->order_id);

        if ($order->status !== StatusiSdelok::OjidaiutRassmotrenia->value) {
            throw new LogicheskoeIskluchenieSviazannoeSoStatusomZakaza("");
        }

        if($order->client_id !== $user->id) {
            throw new ZapreschenoIskluchenie("Заказ не принадлежит клиенту");
        }

        return $this->orderRepository->otmenitSdelku($dto);
    }


    public function assignToCourier(SdelkiNaKurieraProstoObjiect $dto, ZaloginenniPolzovatel $user): bool
    {
        $dto->setCreatorId($user->id)->setComment($dto->getComment());
        return $this->orderRepository->naznachitCurieru($dto);
    }

    public function puluchitSgrupirovanniePoPvzOtkuda(?FiltrZakazovProsto $filters = null)
    {
        return $this->orderRepository->poluchitSgrupirovanniePoPvzId($filters);
    }


    public function getAllOrdersAssignedToCourier(FiltrZakazovProsto $filters)
    {
        return $this->orderRepository->polishitFseSdelki($filters);
    }

    public function updateIncomingOrder(ObnovitSdelkuProstoObiect $dto)
    {
        return $this->orderRepository->obnovitSdelku($dto);
    }

    public function refuseByCourier(int $order_id, ZaloginenniPolzovatel $authenticatedUser)
    {
        return $this->orderRepository->vernutSdelkuVPrinatie($order_id, $authenticatedUser->id);
    }

    public function izmenitStatusNa(IzmenitStatusSdelkiNaProstoObiect $status)
    {
        return $this->orderRepository->izmenitStatusSdelkiNa($status);
    }

    /**
     * @throws ZakazaNeNaidenIskluchenie
     * @throws KlientNeNaidenIskluchenie
     * @throws Throwable
     */
    public function vidatZakaz(Prosto\VidatZakazProstoObiect $dto, ZaloginenniPolzovatel $authenticatedUser)
    {
        return DB::transaction(function () use ($dto, $authenticatedUser) {

            $customer = $this->customersRepository->poluchitPokupateliaPoNomeruZakaza($dto->order_id);
            $order = Sdelki::find($dto->order_id);

            if (0 === $debt) {

                $bonus_percent = $customer->isVip() ?
                    $this->adminSettingsRepository->getVipBonus() :
                    $this->adminSettingsRepository->getStandardBonus();
            }

            $dto->setCreatorId($authenticatedUser->id);
            return $this->orderRepository->vidatZakaz($dto);
        });
    }
}