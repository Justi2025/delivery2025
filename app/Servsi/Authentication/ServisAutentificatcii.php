<?php
 

namespace App\Servsi\Authentication;

use App\Http\Services\Notification\NotificationSender;
use App\Khranilischa\Authentication\AuthenticationRepository;
use App\Khranilischa\Authentication\Token\Exception\InvalidTokenException;
use App\Khranilischa\Authentication\Token\Exception\TokenExpiredException;
use App\Khranilischa\Authentication\Token\Exception\TokenRevokedException;
use App\Khranilischa\Authentication\Token\TokenPair;
use App\Khranilischa\Authentication\Token\TokenPayload;
use App\Khranilischa\Authentication\Token\TokenRepositoryInterface;
use App\Khranilischa\Authentication\Token\UpdateRefreshTokenDto;
use App\Khranilischa\ProverkaNomera\ProverkaNomeraProstoObjiect;
use App\Khranilischa\ProverkaNomera\RepositotiiProverkiNomera;
use App\Khranilischa\RoliPolzovatelei;
use App\Servsi\Telegram\CommandArguments;
use Exception;
use Illuminate\Http\Client\Response;

class ServisAutentificatcii
{

    public function __construct(
        private readonly AuthenticationRepository $authenticationRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly string $jwtSecret,
        private readonly int $refreshTTL,
    )
    {
    }


    /**
     * Creates user account and returns access_token/refresh_token pair
     *
     * @param RegistratciaPolzovateliaProstoObjiect $dto
     * @return TokenPair
     * @throws \Throwable
     */
    public function register(RegistratciaPolzovateliaProstoObjiect $dto): TokenPair
    {
        return $this->authenticationRepository->createUser($dto, $this->jwtSecret);
    }

    /**
     * @throws NotAuthenticatedException
     */
    public function login(array $credentials): TokenPair
    {
        try {
            return $this->authenticationRepository->authenticate($credentials, $this->jwtSecret);
        } catch (Exception $e) {
            throw new NotAuthenticatedException($e->getMessage());
        }
    }


    public function logout(string $access_token): bool
    {
        return $this->tokenRepository->clearSession($access_token);
    }

    public function poluchitPolzovatelia(string $token): ZaloginenniPolzovatel
    {
        $token = $this->tokenRepository->parseToken($token, $this->jwtSecret);
        return new ZaloginenniPolzovatel($token->user_id, RoliPolzovatelei::from($token->user_role_id), office_id: $token->office_id);
    }


    public function getProfileData(string $token)
    {
        $user = $this->poluchitPolzovatelia($token);
        return $this->authenticationRepository->getProfileData($user->id);
    }


    /**
     * Returns access_token/refresh_token pair
     *
     * @param int $uid
     * @param string $user_role
     * @return TokenPair
     */
    public function getTokens(int $uid, string $user_role, string $full_name): TokenPair
    {
        return $this->tokenRepository->generateTokens(
            TokenPayload::getDefault($uid, $user_role, $full_name), $this->jwtSecret
        );
    }


    /**
     * @param UpdateRefreshTokenDto $dto
     * @return TokenPair
     * @throws InvalidTokenException
     * @throws TokenExpiredException
     * @throws TokenRevokedException
     */
    public function refresh(UpdateRefreshTokenDto $dto): TokenPair
    {
        return $this->tokenRepository->refreshTokens($dto, $this->jwtSecret);
    }


    public function getSessions(string $access_token)
    {
        return $this->tokenRepository->getSessions($access_token, $this->jwtSecret);
    }


    public function getAuthenticatedUser(?string $access_token): ?array
    {
        if (null !== $access_token) {
            $token = $this->tokenRepository->parseToken($access_token, $this->jwtSecret);
            return [
                'user_id' => $token->user_id,
                'user_role' => RoliPolzovatelei::from($token->user_role)
            ];
        }

        return null;
    }


    public function sendPhoneVerificationCode(
        string $phone, string $ip, string $user_agent, NotificationSender $notificationSender): Response
    {
        $verificationCode = $this->authenticationRepository->createPhoneVerificationCode(
            $phone, $ip, $user_agent, $notificationSender->getServiceName()
        );

        return $notificationSender->send("Код восстановления доступа: $verificationCode", $phone);
    }


    /**
     * @throws Exception
     */
    public function generateVerificationCommand(
        RepositotiiProverkiNomera $verifications, string $ip, string $ua, string $phone): CommandArguments
    {
        $code = $verifications->generateCode();
        $token = $verifications->generateToken();

        /*if($verifications->contains($phone)) {
            $verifications->
        }
        else {

        }*/

        $data = [
            'phone' => $phone,
            'code' => $code,
            'token' => $token,
            'ip' => $ip,
            'user_agent' => $ua,
            'service' => 'telegram'
        ];

        $verifications->saveToken(ProverkaNomeraProstoObjiect::from($data));

        return CommandArguments::make('verify', $token);
    }
}
