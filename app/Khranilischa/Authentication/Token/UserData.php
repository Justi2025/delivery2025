<?php


namespace App\Khranilischa\Authentication\Token;

class UserData
{

    public function __construct(
        public readonly int $user_id,
        public readonly ?string $role = null,
    )
    {}
}
