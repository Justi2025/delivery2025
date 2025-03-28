<?php


namespace App\Http\Controllers;

use App\Http\Requests\ZaprosVkhoda;
use App\Http\Requests\ZaprosIzmeneniaNomera;
use App\Http\Requests\ZaprosProverkiNomera;
use App\Http\Requests\OtpravitKodProverkiNomeraZapros;
use App\Http\Requests\ZaprosRegistratciiPolzovatelia;
use App\Http\Otveti\InvalidCredentialsResponse;
use App\Http\Otveti\UnauthenticatedResponse;
use App\Http\Services\Notification\NotificationSender;
use App\Models\PhoneVerification;
use App\Models\User;
use App\Khranilischa\Authentication\Token\UpdateRefreshTokenDto;
use App\Khranilischa\ProverkaNomera\ProverkaNomeraProstoObjiect;
use App\Khranilischa\ProverkaNomera\RepositotiiProverkiNomera;
use App\Khranilischa\RoliPolzovatelei;
use App\Khranilischa\UserStatus;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Authentication\NotAuthenticatedException;
use App\Servsi\Authentication\RegistratciaPolzovateliaProstoObjiect;
use App\Servsi\LogicheskoeIskluchenieServica;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;
use Throwable;


class GlavniPoVkhoduNaSait extends Controller
{

    public function __construct(
        private readonly ServisAutentificatcii $servisAutentificatcii
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function register(ZaprosRegistratciiPolzovatelia $request)
    {
        $userRegistrationDto = RegistratciaPolzovateliaProstoObjiect::from(array_merge($request->validated(), [
            'role' => RoliPolzovatelei::Klient->value,
            'status' => UserStatus::Activated->value
        ]));

        $tokenPair = $this->servisAutentificatcii->register($userRegistrationDto);
        return response()->json($tokenPair);
    }


    public function login(ZaprosVkhoda $request, InvalidCredentialsResponse $response)
    {
        $credentials = $request->validated();

        try {
            return response()->json($this->servisAutentificatcii->login($credentials));

        } catch (NotAuthenticatedException) {
            return response()->json($response->toArray(), $response->httpCode);
        }
    }

    public function logout(Request $request)
    {
        $access_token = $request->bearerToken();

        if ($access_token) {
            $this->servisAutentificatcii->logout($access_token);
        }
    }

    public function refresh(Request $request, UnauthenticatedResponse $response)
    {
        $dto = (new UpdateRefreshTokenDto())
                ->setRefreshTokenValue($request->bearerToken())
                ->setRefreshTtl(env('JWT_REFRESH_TOKEN_TTL'))
                ->setUserAgent($request->userAgent());

        try {
            return response()->json($this->servisAutentificatcii->refresh($dto));
        } catch (Exception $e) {
            Log::log(LogLevel::DEBUG, $e);
        }

        return response()->json($response->toArray(), $response->httpCode);
    }


    /**
     * @throws Exception
     */
    public function sendCode(OtpravitKodProverkiNomeraZapros $request, NotificationSender $notificationSender)
    {
        $phone = $request->validated('phone');

        $command = $this->servisAutentificatcii->generateVerificationCommand(
            app()->make(RepositotiiProverkiNomera::class),
            $request->ip(),
            $request->userAgent(),
            $phone
        );

        return ['url' => env('TELEGRAM_BOT_URL') . "?start=" . $command];
    }

    public function checkCode(Request $request)
    {
        $phone = $request->input('phone');
        $code = $request->input('code');

        $conditions = ['phone' => $phone, 'code' => $code];

        if(!PhoneVerification::where($conditions)->exists()) {
            throw new Exception('Неверный код');
        }
        return ['code' => $code];
    }


    public function sendRecoveryCode(ZaprosProverkiNomera $request, NotificationSender $notificationSender)
    {
        $phone = $request->validated('phone');

        $response = $this->servisAutentificatcii->sendPhoneVerificationCode(
            $phone, $request->ip(), $request->userAgent(),
            $notificationSender
        );

        return ['status' => $response->status()];
    }


    /**
     * @throws LogicheskoeIskluchenieServica
     */
    public function changePassword(ZaprosIzmeneniaNomera $request)
    {
        $phone = $request->validated('phone');
        $code = $request->validated('code');
        $password = $request->validated('password');

        $model = PhoneVerification::where(['phone' => $phone, 'code' => $code]);

        if(!$model->exists())
            throw new LogicheskoeIskluchenieServica('Неверный код!');

        $userModel = User::where(['phone' => $phone])->first();
        $userModel->password = Hash::make($password);
        $userModel->save();

        return $userModel;
    }


    public function getAuthenticated(Request $request)
    {
        return $this->servisAutentificatcii->getProfileData($request->bearerToken());
    }


    public function getUser(Request $request)
    {
        return $this->servisAutentificatcii->getAuthenticatedUser($request->bearerToken());
    }

    public function userSessions(Request $request)
    {
        return $this->servisAutentificatcii->getSessions($request->bearerToken());
    }
}
