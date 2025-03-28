<?php


namespace App\Http\Controllers\NastroikiSaita;

use App\Common\Primesi\ApiResponse;
use App\Http\Controllers\Controller;
use App\Khranilischa\User\RepositoriPolzovatelei;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Nastroiki\ServicNatroekPolzovatelia;
use App\Servsi\Telegram\CommandArguments;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlavniPoNastroikamPolzovatelia extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ServisAutentificatcii  $authenticationService,
        private readonly RepositoriPolzovatelei $usersRepository,
        private readonly ServicNatroekPolzovatelia $userSettingsService
    )
    {
    }

    public function getAll(Request $request): JsonResponse
    {
        $user = $this->authenticationService->poluchitPolzovatelia($request->bearerToken());
        return $this->response($this->userSettingsService->getAllSettings($user->id));
    }

    public function setTelegramNotifications(Request $request)
    {
        $status = $request->input('status');
        $user = $this->authenticationService->poluchitPolzovatelia($request->bearerToken());

        if ($this->usersRepository->hasConnectedTelegram($user->id)) {
            return $this->response($this->userSettingsService->setTelegramNotifications($status, $user->id));
        }

        $args = $this->userSettingsService->encodeTelegramConnectArgs($user->id, $status);
        $command = CommandArguments::make('connect', $args);
        $bot_url = env('APP_DEBUG') ? env('TELEGRAM_DEV_BOT_URL') : env('TELEGRAM_BOT_URL');

        return ['url' => $bot_url . "?start=" . $command];
    }
}
