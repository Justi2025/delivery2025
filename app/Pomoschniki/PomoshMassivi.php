<?php
 

namespace App\Pomoschniki;

class PomoshMassivi
{
    public static function udalitNuli(array $array): array
    {
        return array_filter($array, self::ne_nul(...));
    }

    private static function ne_nul(mixed $value): bool
    {
        return !is_null($value);
    }
}