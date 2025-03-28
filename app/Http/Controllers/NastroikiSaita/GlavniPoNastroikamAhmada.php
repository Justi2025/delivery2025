<?php
 

namespace App\Http\Controllers\NastroikiSaita;

use App\Common\Primesi\ApiResponse;
use App\Http\Controllers\Controller;
use App\Khranilischa\User\RepositoriPolzovatelei;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Nastroiki\ServisNatroekAhmada;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlavniPoNastroikamAhmada extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ServisNatroekAhmada    $servisNatroekAhmada,
        private readonly ServisAutentificatcii  $servisAutentificatcii,
        private readonly RepositoriPolzovatelei $repositoriPolzovatelei,
    )
    {
    }

    public function poluchitVse(): JsonResponse
    {
        return $this->response($this->servisNatroekAhmada->getAllSettings());
    }

    public function ustanovitStandartniBonus(Request $request): JsonResponse
    {
        return $this->response($this->servisNatroekAhmada->setStandardBonus($request->input('amount')));
    }


    public function ustanovitVipBonus(Request $request): JsonResponse
    {
        return $this->response($this->servisNatroekAhmada->setVipBonus($request->input('amount')));
    }


    public function ustanovitVidimostOtchetov(Request $request): JsonResponse
    {
        $visibility = $request->input('reports_visible');
        return $this->response($this->servisNatroekAhmada->setReportVisibilityForManager($visibility));
    }
}
