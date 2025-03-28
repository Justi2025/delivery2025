<?php


namespace App\Khranilischa\Sdelki\Perechislenia;

enum OrderType2 : int
{
    case Delivery = 1;
    case Shipment = 2;
    case Return = 3;

    public function name(): string
    {
        return match ($this) {
            self::Delivery => 'delivery',
            self::Shipment => 'shipment',
            self::Return => 'return',
        };
    }

    public static function casesAsString():string {
        return  implode(',', array_map(fn($case) => $case->value, self::cases()));
    }
}