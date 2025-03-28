<?php
 

namespace App\Http\Controllers\Sdelki;

use App\Common\Perechislenia\FailoviTipPerechislenie;
use App\Common\Exceptions\ZapreschenoIskluchenie;
use App\Events\Zakaz\StatusZakazaIzmenenSobitie;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sdelki\NaznachitSdelkuNaKurieraZapros;
use App\Http\Requests\Sdelki\IzemitStatusSdelkiZaproz;
use App\Http\Requests\Sdelki\IzmenitStatusNaZapros;
use App\Http\Requests\Sdelki\SozdatSdelkuSotrudnikomZapros;
use App\Http\Requests\Sdelki\ZaprosFiltraciiSdelok;
use App\Http\Requests\Sdelki\ObnovitSdelkuZapros;
use App\Http\Requests\Sdelki\VidatZakazZapros;
use App\Http\Requests\Sdelki\OtmenitSdelkuZapros;
use App\Http\Requests\Sdelki\OtklonitSdelkuZapros;
use App\Http\Requests\Sdelki\ObnovitShtrikhKodZapros;
use App\Http\Requests\SokranitFailZapros;
use App\Models\Sdelki;
use App\Models\IstoriaZakaza;
use App\Models\User;
use App\Khranilischa\BonusiKlientov\BonusRepo;
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
use App\Khranilischa\Sdelki\ZakazaNeNaidenIskluchenie;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Faili\ServisZagruzkiFailov;
use App\Servsi\Sdelki\Prosto\VidatZakazProstoObiect;
use App\Servsi\Sdelki\LogicheskoeIskluchenieSviazannoeSoStatusomZakaza;
use App\Servsi\Sdelki\ServizSdelok;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class GlavniPoSdelkam extends Controller
{

    public function __construct(
        private readonly ServizSdelok          $servizSdelok,
        private readonly ServisAutentificatcii $servisAutentificatcii,
        private readonly ServisZagruzkiFailov  $servisZagruzkiFailov,
    )
    {

    }

    public function poluchitVseZakazi(ZaprosFiltraciiSdelok $zaprosFiltraciiSdelok)
    {
        return response()->json($this->otfiltrovatIOtsortirovat($zaprosFiltraciiSdelok, $zaprosFiltraciiSdelok->path()));
    }


    public function izmenitStatusNa(IzmenitStatusNaZapros $request)
    {
        $status = IzmenitStatusSdelkiNaProstoObiect::from($request->validated());
        return ['result' => $this->servizSdelok->izmenitStatusNa($status)];
    }


    public function poluchitVseZakaziNaznachennieNaKuriera(ZaprosFiltraciiSdelok $request)
    {
        $filters = FiltrZakazovProsto::create();
        $filters->couriers = [2];
        $filters->order_statuses = [StatusiSdelok::NaznacheniNaKuriera];

        return $this->servizSdelok->getAllOrdersAssignedToCourier($filters);
    }


    public function poluchitNomertelephonaVladelcaZakaza(int $order_id): JsonResponse
    {
        return response()->json(['phone' => $this->servizSdelok->poluchitNomertelephonaVladelcaZakaza($order_id)]);
    }


    public function ojidautRassmotrenia(ZaprosFiltraciiSdelok $request)
    {
        return $this->otfiltrovatIOtsortirovat($request, $request->path(), function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) {
            $filter->order_statuses = [StatusiSdelok::OjidaiutRassmotrenia];
        });
    }


    public function prinatiiSdelki(ZaprosFiltraciiSdelok $request, ?int $iden_pvz)
    {
        $filtrator = function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($iden_pvz) {
            $filter->order_statuses = [StatusiSdelok::Prinati];

            if ($iden_pvz) {
                $filter->addresses_from = [$iden_pvz];
            }
        };

        return $this->otfiltrovatIOtsortirovat($request, $request->path(), $filtrator);
    }


    public function poluchitNaznachennieNaKurieraSdelki(ZaprosFiltraciiSdelok $zapros, ?int $iden_pvz)
    {
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($zapros->bearerToken());

        $filterCallback = function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($iden_pvz, $user) {
            $filter->order_statuses = [StatusiSdelok::NaznacheniNaKuriera];

            if ($iden_pvz) {
                $filter->addresses_from = [$iden_pvz];
            }

            if ($user->isCourier()) {
                $filter->couriers = [$user->id];
            }
        };

        return $this->otfiltrovatIOtsortirovat($zapros, $zapros->path(), $filterCallback);
    }


    public function poluchitZakaziPoluchennieKurierom(ZaprosFiltraciiSdelok $zapros, ?int $identif_pvz)
    {
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($zapros->bearerToken());

        return $this->otfiltrovatIOtsortirovat($zapros, $zapros->path(), function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($user, $identif_pvz) {
            $filter->order_statuses = [StatusiSdelok::PolucheniKurierom];

            if ($user->isCourier()) {
                $filter->couriers = [$user->id];
                $filter->addresses_from = [$identif_pvz];
            }

            if ($identif_pvz) {
                $filter->addresses_from = [$identif_pvz];
            }
        });
    }

    public function gotoviKVidache(ZaprosFiltraciiSdelok $request)
    {
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());

        return $this->otfiltrovatIOtsortirovat($request, $request->path(), function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($user) {
            $filter->order_statuses = [StatusiSdelok::OjidautPokupatelia];

            if ($user->isOperator() && $user->office_id !== null) {
                $filter->addresses_to = [$user->office_id];
            }
        });
    }


    public function vidanniePokupateliu(ZaprosFiltraciiSdelok $request)
    {
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());

        return $this->otfiltrovatIOtsortirovat($request, $request->path(), function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($user) {
            $filter->order_statuses = [
                StatusiSdelok::Vidan,
                StatusiSdelok::VidanOplachenChastichno,
                StatusiSdelok::DolgPokupateliaZakrit
            ];

            if ($user->isOperator() && $user->office_id !== null) {
                $filter->addresses_to = [$user->office_id];
            }
        });
    }


    public function poluchitOtmenennieZakazi(ZaprosFiltraciiSdelok $request)
    {
        return $this->otfiltrovatIOtsortirovat($request, $request->path(), function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) {
            $filter->order_statuses = [StatusiSdelok::Otmenen];
        });
    }


    public function poluchitTekushieZakaziPolzovatelei(ZaprosFiltraciiSdelok $zapros, int $id_klienta)
    {
        $filterCallback = function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($id_klienta) {
            $current_order_statuses = array_filter(StatusiSdelok::cases(), fn(StatusiSdelok $sts) => $sts !== StatusiSdelok::Vidan);
            $filter->order_statuses = $current_order_statuses;
            $filter->customer_id = $id_klienta;
        };

        return $this->otfiltrovatIOtsortirovat($zapros, $zapros->path(), $filterCallback);
    }


    public function poluchitZavershennirSdelki(ZaprosFiltraciiSdelok $zapros, int $id_klienta)
    {
        $filterCallback = function (ProstoiObiectSortirovkiZakaza $sorter, FiltrZakazovProsto $filter) use ($id_klienta) {
            $filter->order_statuses = [StatusiSdelok::Vidan];
            $filter->customer_id = $id_klienta;
        };

        return $this->otfiltrovatIOtsortirovat($zapros, $zapros->path(), $filterCallback);
    }

    public function puluchitSgrupirovanniePoPvzOtkuda(ZaprosFiltraciiSdelok $request)
    {
        $validated = $request->validated();
        

        $orderFilters = null;
        if ($request->has('filters')) {
            $orderFilters = FiltrZakazovProsto::from($validated['filters']);
        }

        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        if ($user->isCourier()) {
            $orderFilters->couriers = [$user->id];
        }


        return $this->servizSdelok->puluchitSgrupirovanniePoPvzOtkuda($orderFilters);
    }


    public function zagruzitFotoZakaza(SokranitFailZapros $request)
    {
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        $destination = $request->validated('dest', 'uploads');
        $file = $request->validated('file');
        $order_id = $request->validated('fileable_id', '');

        return $this->servisZagruzkiFailov->zagruzit($file, $user, $destination, IstoriaZakaza::class, $order_id)->toArray();
    }


    public function izmeniStatusSdelki(IzemitStatusSdelkiZaproz $request): JsonResponse|array
    {
        $authenticatedUser = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        $validated = $request->validated();

        $statusDto = StatusZakazaProstoObiect::from($validated);
        $incomingDto = DostavkaProstoObjiect::from($validated);


        $photo_ids = [];
        if ($request->has('photos')) {
            $order_photos = $request->file('photos');

            foreach ($order_photos as $photo) {
                $file = $this->servisZagruzkiFailov->zagruzit($photo, $authenticatedUser);
                $photo_ids[] = $file->id;
            }

            $statusDto->setPhotos($photo_ids);
        }
        
        $order = Sdelki::find($incomingDto->getId());

        if (is_null($order->customer?->id)) {
            throw new LogicheskoeIskluchenieSviazannoeSoStatusomZakaza("Такого клиента нет");
        }

        $incomingDto->setCustomerId($order->customer->id);

        if ($statusDto->getStatusCode() === StatusiSdelok::Vidan) {
            /** @var BonusRepo $bonusRepository */
            $bonusRepository = app()->make(BonusRepo::class);

            $result = $this->servizSdelok->izmenitStatusSdelki($statusDto, $incomingDto, $authenticatedUser, $bonusRepository);
            if ($result) {
                event(new StatusZakazaIzmenenSobitie(
                    $result, $statusDto, $authenticatedUser, $order->customer->telegram_chat_id));

                return $result->toArray();
            }
        }

        $result = $this->servizSdelok->izmenitStatusSdelki($statusDto, $incomingDto, $authenticatedUser);

        if ($result) {

            event(new StatusZakazaIzmenenSobitie(
                $result, $statusDto, $authenticatedUser, $order->customer->telegram_chat_id));
        }


        return $result->toArray();
    }


    public function obnovitSdelku(ObnovitSdelkuZapros $request)
    {
        $dto = ObnovitSdelkuProstoObiect::from($request->validated());

        return ['result' => $this->servizSdelok->updateIncomingOrder($dto)];
    }
    
    public function obnovitShtrikhKodSdelki(ObnovitShtrikhKodZapros $request)
    {
        $authenticatedUser = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());

        $dto = DostavkaProstoObjiect::from($request->validated());

        $file = $this->servisZagruzkiFailov->zagruzit($request->file('barcode_image'), $authenticatedUser);
        $dto->setBarcodeImage($file->generated_name);

        return ['result' => $this->servizSdelok->updateBarcodeImage($dto)];
    }


    /**
     * @throws ZapreschenoIskluchenie
     */
    public function poluchitSdelkuPoId(Request $request, int $id)
    {
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        return response()->json($this->servizSdelok->getIncomingById($id, $user));
    }


    public function naznachitKurieru(NaznachitSdelkuNaKurieraZapros $request)
    {
        $dto = SdelkiNaKurieraProstoObjiect::from($request->validated());
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        return response()->json($this->servizSdelok->assignToCourier($dto, $user));
    }


    public function sozdatSdelku(SozdatSdelkuSotrudnikomZapros $request)
    {
        $authenticatedUser = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        $orderCreateDto = null;

        if ($authenticatedUser->isAdmin() || $authenticatedUser->isManager()) {
            $orderCreateDto = SozdatZakazProstoObiect::fromEmployee($request->validated());
        }

        if ($authenticatedUser->isCustomer()) {
            $orderCreateDto = SozdatZakazProstoObiect::fromCustomer($request->validated());
        }

        if (TEST) {
            if ($request->has('barcode_images')) {
                $barcode_images = $request->file('barcode_images');

                foreach ($barcode_images as $barcode_image) {
                    $this->servisZagruzkiFailov->uploadX(
                        $barcode_image, $authenticatedUser,
                        FailoviTipPerechislenie::OrderBarcode, $newOrder->getId()
                    );
                }
            }
        }


        $user = User::find($newOrder->getCustomerId());
        $orderStatusDto = StatusZakazaProstoObiect::create($newOrder->getId(), $newOrder->getOrderStatus());

        event(new StatusZakazaIzmenenSobitie($newOrder, $orderStatusDto, $authenticatedUser, $user->telegram_chat_id));

        return $newOrder->toArray();
    }


    /**
     * @throws ZapreschenoIskluchenie
     */
    public function otmeniSdelku(OtmenitSdelkuZapros $request)
    {
        $authenticatedUser = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        $dto = OtmenitSdelkuProsoObjiect::from(array_merge($request->validated(), ['creator_id' => $authenticatedUser->id]));
        return ['result' => $this->servizSdelok->cancelOrder($dto, $authenticatedUser)];
    }


    public function otmenitSdelkuKurierom(OtklonitSdelkuZapros $request)
    {
        $authenticatedUser = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        $order_id = intval($request->validated('order_id'));
        return ['result' => $this->servizSdelok->refuseByCourier($order_id, $authenticatedUser)];
    }


    public function vidatZakaz(VidatZakazZapros $vidatZakazZapros)
    {
        $authenticatedUser = $this->servisAutentificatcii->poluchitPolzovatelia($vidatZakazZapros->bearerToken());
        $dto = VidatZakazProstoObiect::from($vidatZakazZapros->validated());
        $statusDto = StatusZakazaProstoObiect::create($dto->order_id, StatusiSdelok::Vidan->value);

        $order = $this->servizSdelok->vidatZakaz($dto, $authenticatedUser);

        if ($order) {
            event(new StatusZakazaIzmenenSobitie($order, $statusDto, $authenticatedUser, $order->getTelegramChatId()));
        }

        return ['result' => (bool)$order];
    }
}
