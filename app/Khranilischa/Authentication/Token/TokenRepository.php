<?php


namespace App\Khranilischa\Authentication\Token;

use App\Common\Primesi\ToJson;
use App\Pomoschniki\PomoshToken;
use App\Models\RefreshToken;
use App\Models\User;
use App\Khranilischa\Authentication\Token\Exception\InvalidTokenException;
use App\Khranilischa\Authentication\Token\Exception\TokenExpiredException;
use App\Khranilischa\Authentication\Token\Exception\TokenRevokedException;
use Cache;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Str;


class TokenRepository implements TokenRepositoryInterface
{
    use ToJson;

    public function __construct(
        private readonly PomoshToken $jwt
    )
    {
    }

    public function parseToken(string $access_token, string $secret): Token
    {
        $decoded = $this->jwt->decodirovat($access_token, $secret);
        return Token::from($decoded);
    }

    public function getSessions(string $access_token, string $secret)
    {
        $token = $this->parseToken($access_token, $secret);
        return RefreshToken::where(['user_id' => $token->user_id])->get();
    }


    public function generateTokens(TokenPayload $payload, string $secret): TokenPair
    {
        $access_token_value = $this->jwt->kodirovat($payload->toArray(), $secret);
        $refresh_token_value = Str::random(64);

        $model = RefreshToken::where([
            'user_id' => $payload->user_id,
            'user_agent' => $payload->user_agent,
        ]);

        if($model->exists()) {
            $model->update([
                'access_token_md5' => md5($access_token_value),
                'user_ip' => $payload->ip,
                'value' => $refresh_token_value
            ]);
        }
        else {
            RefreshToken::create([
                'access_token_md5' => md5($access_token_value),
                'user_id' => $payload->user_id,
                'user_ip' => $payload->ip,
                'user_agent' => $payload->user_agent,
                'value' => $refresh_token_value
            ]);
        }

        return new TokenPair(
            $access_token_value,
            $refresh_token_value
        );
    }


    /**
     * @throws TokenExpiredException
     * @throws InvalidTokenException
     * @throws TokenRevokedException
     */
    public function refreshTokens(UpdateRefreshTokenDto $dto, string $secret): TokenPair
    {
        /** @var Builder $model */
        $model = RefreshToken::where([
            'value' => $dto->getRefreshTokenValue(),
            'user_agent' => $dto->getUserAgent()
        ]);

        if ($model->exists()) {
            /** @var RefreshToken $refresh_token */
            $refresh_token = $model->first();

            if ($refresh_token->is_revoked) {
                throw new TokenRevokedException();
            }

            if ($refresh_token->expired($dto->getRefreshTtl())) {
                throw new TokenExpiredException();
            }

            $user = User::find($refresh_token->user_id);

            return $this->generateTokens(TokenPayload::getDefault($user->id, $user->role, $user->full_name, office_id: $user->office_id), $secret);
        } else {
            throw new InvalidTokenException($this->toJson([
                'refresh_token_value' => $dto->getRefreshTokenValue(),
                'refresh_exists' => $model->exists()
            ]));
        }
    }

    public function revokeRefresh(string $value)
    {
        $model = RefreshToken::where(['value' => $value]);
        return $model->update(['is_revoked' => 1]);
    }

    public function revokeAllRefreshTokens(int $uid)
    {
        $model = RefreshToken::where(['user_id' => $uid]);
        return $model->update(['is_revoked' => 1]);
    }


    public function clearSession(string $access_token): bool
    {
        $access_token_hash = md5($access_token);
        $model = RefreshToken::where(['access_token_md5' => $access_token_hash]);

        if ($model->exists()) {
            $model->delete();
            Cache::put($access_token_hash, false);

            return true;
        }

        return false;
    }

}
