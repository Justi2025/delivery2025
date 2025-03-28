<?php


namespace App\Servsi\Pvz;

use App\Khranilischa\Pvz\PvzRepo;
use App\Khranilischa\Pvz\ProstieObjiecti\PvzPO;
use App\Khranilischa\Pvz\ProstieObjiecti\PvzFilter;
use App\Servsi\Authentication\ZaloginenniPolzovatel;

class ServicPvzs
{
    public function __construct(
        private readonly PvzRepo $deliveryPointsRepository
    )
    {
    }

    public function getAll(PvzFilter $filterDto)
    {
        return $this->deliveryPointsRepository->getAll($filterDto);
    }


    public function create(PvzPO $dto, ZaloginenniPolzovatel $user)
    {
        $dto->setCreatorId($user->id);

        return $this->deliveryPointsRepository->create($dto);
    }
}