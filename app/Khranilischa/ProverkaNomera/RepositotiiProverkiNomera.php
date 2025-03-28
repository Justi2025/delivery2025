<?php
 

namespace App\Khranilischa\ProverkaNomera;


use App\Models\PhoneVerification;
use Exception;

class RepositotiiProverkiNomera
{

    public function __construct()
    {
    }

    public function saveToken(ProverkaNomeraProstoObjiect $verificationDto)
    {
        return PhoneVerification::create($verificationDto->toArray());
    }


    public function containsToken(string $token): bool
    {
        return PhoneVerification::where(['token' => $token])->exists();
    }

    public function getVerification(string $token): ?ProverkaNomeraProstoObjiect
    {
        $model = PhoneVerification::where(['token' => $token])->latest()->first();

        return null !== $model ? ProverkaNomeraProstoObjiect::from($model->toArray()) : null;
    }

    /**
     * @throws Exception
     */
    public function generateCode(int $min = 100000, int $max = 999999): int
    {
        return random_int($min, $max);
    }

    /**
     * @throws Exception
     */
    public function generateToken(int $length = 10): string
    {
        return bin2hex(random_bytes($length));
    }
}