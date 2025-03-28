<?php


namespace App\Khranilischa\Sdelki\Perechislenia;

trait EnumCasesToString
{
    public static function casesAsString():string {
        //return  implode(',', array_map(fn($case) => $case->value !=0 && $case->value, self::cases()));
        return  implode(',', array_map(fn($case) => $case->value, self::cases()));
    }
}