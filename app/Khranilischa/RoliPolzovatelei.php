<?php
 

namespace App\Khranilischa;

use App\Khranilischa\Sdelki\Perechislenia\EnumCasesToString;

enum RoliPolzovatelei: int
{
    use EnumCasesToString;

    case Ahmad = 1;
    case ZamAhmada = 2;
    case Voditel = 3;
    case Skladovschik = 4;
    case Klient = 5;
    case Kassir = 6;

    public function name(): string
    {
        return match ($this) {
            self::Ahmad => 'admin',
            self::ZamAhmada => 'manager',
            self::Voditel => 'courier',
            self::Skladovschik => 'storekeeper',
            self::Klient => 'customer',
            self::Kassir => 'operator',
        };
    }
}