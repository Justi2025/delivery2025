<?php


namespace App\Common\Primesi;

use DateTime;
use Exception;

trait GetDate
{
    public static function getDate(?string $date): ?DateTime
    {
        if (!$date) return null;

        try {
            return new DateTime($date);
        } catch (Exception) {
            return null;
        }
    }
}