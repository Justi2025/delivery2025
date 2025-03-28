<?php
 

namespace App\Http\Controllers\Otcheti;

use App\Http\Controllers\Controller;
use App\Http\Requests\Otcheti\OtchetFiltraciiZapros;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Otcheti\Prosto\FiltrOtchetovProsto;
use App\Servsi\Otcheti\ServicOtchetov;
use Illuminate\Pagination\Paginator;

class GlavniPoOtchetam extends Controller
{

    public function __construct(
        private readonly ServicOtchetov        $servisOtchetov,
        private readonly ServisAutentificatcii $servisAutentificatcii
    )
    {
    }

    public function poluchitPlatejiPoDniamIPvz(OtchetFiltraciiZapros $request)
    {
        Paginator::currentPathResolver(fn() => '/reports');

        $user = $this->servisAutentificatcii->poluchitPolzovatelia($request->bearerToken());
        $filter = FiltrOtchetovProsto::from($request->validated());

        return $this->servisOtchetov->poluchitPlatejiPoDniamIPvz($filter, $user);
    }

    public function poluchitPlatejiPoDniam(OtchetFiltraciiZapros $request)
    {
        Paginator::currentPathResolver(fn() => '/reports');

        $filter = FiltrOtchetovProsto::from($request->validated());
        return $this->servisOtchetov->poluchitPlatejiPoDniam($filter);
    }

    public function dolgiKlientov()
    {
        Paginator::currentPathResolver(fn() => '/reports');

        return $this->servisOtchetov->dolgiKlientov();
    }
}
