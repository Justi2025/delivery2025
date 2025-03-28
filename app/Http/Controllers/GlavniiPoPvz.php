<?php
 

namespace App\Http\Controllers;

use App\Common\Primesi\ToJson;
use App\Http\Requests\SozdatPvzZapros;
use App\Http\Requests\Pvzs\FiltrPvzsZapros;
use App\Http\Requests\Pvzs\ObnovitChastotuIspolzovaniaPvzZapros;
use App\Models\Pvz;
use App\Khranilischa\Pvz\ProstieObjiecti\PvzPO;
use App\Khranilischa\Pvz\ProstieObjiecti\PvzFilter;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Pvz\ServicPvzs;

class GlavniiPoPvz extends Controller
{
    use ToJson;

    public function __construct(
        private readonly ServisAutentificatcii $authenticationService,
        private readonly ServicPvzs $servicPvz
    )
    {
    }


    public function index(FiltrPvzsZapros $request)
    {
        $filterDto = PvzFilter::from($request->validated());
        return $this->servicPvz->getAll($filterDto);
    }


    public function sozdat(SozdatPvzZapros $zapros)
    {
        $user = $this->authenticationService->poluchitPolzovatelia($zapros->bearerToken());
        $deliveryPointDto = PvzPO::from($zapros->validated());

        return $this->servicPvz->create($deliveryPointDto, $user);
    }

    public function poluchit(Pvz $pvz)
    {
        return $pvz;
    }

    public function updateUsageFrequency(Pvz $deliveryPoint, ObnovitChastotuIspolzovaniaPvzZapros $request)
    {
        $usage_frequency = $request->input('usage_frequency');
        $result = $deliveryPoint->update(['usage_frequency' => $usage_frequency]);

        return $result ? $deliveryPoint->toArray() : [];
    }

    public function update(Pvz $deliveryPoint, SozdatPvzZapros $request)
    {
        $deliveryPointDto = PvzPO::from($request->validated());

        $result = $deliveryPoint->update($deliveryPointDto->toArray());

        return $result ? $deliveryPoint->toArray() : [];
    }
}
