<?php
 

namespace App\Servsi\Otcheti;

use App\Common\Primesi\GetDate;
use App\Models\User;
use App\Khranilischa\Authentication\AuthenticationRepository;
use App\Khranilischa\Otcheti\RepositoriOtchetov;
use App\Servsi\Authentication\ZaloginenniPolzovatel;
use App\Servsi\Otcheti\Prosto\FiltrOtchetovProsto;
use Illuminate\Pagination\LengthAwarePaginator;

class ServicOtchetov
{
    use GetDate;

    public function __construct(
        private readonly RepositoriOtchetov       $reportRepository,
        private readonly AuthenticationRepository $authenticationRepository
    )
    {
    }

    public function poluchitPlatejiPoDniamIPvz(FiltrOtchetovProsto $filter, ZaloginenniPolzovatel $user): LengthAwarePaginator
    {
        if ($user->isOperator())
        {
            /** @var User $operator */
            $operator = $this->authenticationRepository->getProfileData($user->id);

            $filter = new FiltrOtchetovProsto(
                day: self::getDate(date('Y-m-d')), //new \DateTime('2024-02-22'), //
                office: $operator->office_id,
            );
            return $this->reportRepository->getPaymentsByDayAndPickupPoints($filter);
        }

        return $this->reportRepository->getPaymentsByDayAndPickupPoints($filter);
    }


    public function poluchitPlatejiPoDniam(FiltrOtchetovProsto $filter): LengthAwarePaginator
    {
        return $this->reportRepository->getPaymentsByDay($filter);
    }

    public function dolgiKlientov(): LengthAwarePaginator
    {
        return $this->reportRepository->getDebtsByCustomers();
    }
}