<?php


namespace App\Khranilischa\Sdelki\Perechislenia;

enum OrderType1 : int
{
    case Incoming = 1;
    case Outgoing = 2;

    public function name(): string
    {
        return match ($this) {
            self::Incoming => 'incoming',
            self::Outgoing => 'outgoing',
        };
    }

    public static function casesAsString():string {
        return  implode(',', array_map(fn($case) => $case->value, self::cases()));
    }
}