<?php
 

namespace App\Http\Controllers;


use App\Http\Requests\Bonusi\KolichestvoBonusovZapros;
use App\Khranilischa\BonusiKlientov\ChangeBonusException;
use App\Khranilischa\BonusiKlientov\Po\ProstoObjiectSBonusami;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Bonusi\ServisBonusov;

class GlavniPoIstoriiBonusov extends Controller
{
    public function __construct(
        private readonly ServisAutentificatcii $servisAutentificatcii,
        private readonly ServisBonusov $servisBonusov
    )
    {

    }

    public function podkinutBonusov(KolichestvoBonusovZapros $request)
    {
        $bonusQuantity = ProstoObjiectSBonusami::from($request->validated());
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());

        return response()->json($this->servisBonusov->podkinutBonusov($bonusQuantity, $user));
    }


    /**
     * @throws ChangeBonusException
     */
    public function otobratBonusi(KolichestvoBonusovZapros $request)
    {
        $bonusQuantity = ProstoObjiectSBonusami::from($request->validated());
        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());

        return response()->json($this->servisBonusov->otobratBonusi($bonusQuantity, $user));
    }
}
