<?php
 

namespace App\Khranilischa\Authentication\Token\Exception;

class TokenRevokedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Token revoked");
    }
}
