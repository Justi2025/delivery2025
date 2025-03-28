<?php
 

namespace App\Khranilischa;

enum UserStatus : int
{
    case Activated = 1;
    case Blocked = 2;

    public function name(): string
    {
        return match ($this) {
            self::Activated => 'activated',
            self::Blocked => 'blocked'
        };
    }
}