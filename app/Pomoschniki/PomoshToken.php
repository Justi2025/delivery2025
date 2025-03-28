<?php


namespace App\Pomoschniki;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PomoshToken
{
    public function kodirovat(array $payload, string $key): string
    {
        return JWT::encode($payload, $key, 'HS256');
    }

    public function decodirovat(string $token, string $key): array
    {
        return (array)JWT::decode($token, new Key($key, 'HS256'));
    }
}
