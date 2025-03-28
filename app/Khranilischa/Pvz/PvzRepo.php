<?php
 

namespace App\Khranilischa\Pvz;

use App\Models\Pvz;
use App\Khranilischa\Pvz\ProstieObjiecti\PvzPO;
use App\Khranilischa\Pvz\ProstieObjiecti\PvzFilter;

class PvzRepo
{
    public function getAll(PvzFilter $filterDto)
    {
        $query = Pvz::query();

        if(null !== $filterDto->getCountry()) {
            $query->where(['country' => $filterDto->getCountry()]);
        }

        return $query->with('company')
            ->orderBy('company_id')
            ->orderBy('usage_frequency', 'desc')
            ->paginate(100);
    }

    public function create(PvzPO $dto)
    {
        return Pvz::create($dto->toArray());
    }
}