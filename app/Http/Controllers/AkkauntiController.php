<?php


namespace App\Http\Controllers;

use App\Http\Requests\Akkaunti\IzmenitParolZapros;
use App\Http\Requests\Akkaunti\ObnovitAdresPolzovateliaZapros;
use App\Http\Requests\SozdatAkkauntSotrudnikomZapros;
use App\Http\Requests\SozdatAkkauntZapros;
use App\Http\Requests\SokranitFailZapros;
use App\Http\Services\ServicDeistviiPolzovatelei;
use App\Models\User;
use App\Khranilischa\Akkaunti\Prosto\SozdatAkkauntProsto;
use App\Khranilischa\Authentication\UserNotFoundException;
use App\Servsi\Akkaunti\ServicAkkauntov;
use App\Servsi\Akkaunti\Prosto\IzmenitParolProsto;
use App\Servsi\Akkaunti\Prosto\AdresPolzovatliaProsto;
use App\Servsi\Authentication\ZaloginenniPolzovatel;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Faili\ServisZagruzkiFailov;
use Exception;

class AkkauntiController extends Controller
{
    private readonly ?ZaloginenniPolzovatel $user;

    public function __construct(
        private readonly ServicDeistviiPolzovatelei $servicDeistviiPolzovatelei,
        private readonly ServisZagruzkiFailov       $servisZagruzkiFailov,
        private readonly ServicAkkauntov            $servicAkkauntov,
    )
    {

    }

    public function createByCustomer(SozdatAkkauntZapros $request)
    {
        $params = $request->validated();

        if ($user = User::create($params)) {
            $this->servicDeistviiPolzovatelei->log($request, $user->id);
            return $user;
        }

        throw new Exception('Нет возможности создать клиента');
    }


    public function sozdatAkkauntSotrudnikom(SozdatAkkauntSotrudnikomZapros $request, ServisAutentificatcii $authenticationService)
    {
        $params = $request->validated();
        $this->user = $authenticationService->getAuthenticatedUser(\request()->bearerToken());

        if($this->user->isAdmin() || $this->user->isManager()) {
            $params['phone_verified_at'] = now();
        }

        return SozdatAkkauntProsto::from($params);
    }


    public function obnovitAvatar(SokranitFailZapros $request, ServisAutentificatcii $authenticationService): array
    {
        $user = $authenticationService->poluchitPolzovatelia($request->bearerToken());
        $file = $request->validated('file');

        $created = $this->servisZagruzkiFailov->zagruzit($file, $user, 'avatars', User::class, $user->id);
        return ['result' => $this->servicAkkauntov->updateAvatar($user, $created)];
    }


    public function obnovitAdres(ObnovitAdresPolzovateliaZapros $request, ServisAutentificatcii $authenticationService)
    {
        $user = $authenticationService->poluchitPolzovatelia($request->bearerToken());
        return ['result' => $this->servicAkkauntov->updateAddress(AdresPolzovatliaProsto::from($request->validated()), $user)];
    }


    /**
     * @throws UserNotFoundException
     */
    public function izmenitParol(IzmenitParolZapros $request, ServisAutentificatcii $authenticationService)
    {
        $user = $authenticationService->poluchitPolzovatelia($request->bearerToken());
        $dto = IzmenitParolProsto::from($request->validated());

        return ['result' => $this->servicAkkauntov->izmenitParol($dto, $user)];
    }
}
