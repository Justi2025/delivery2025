<?php
 

namespace App\Khranilischa\Authentication\Token\Exception;

class TokenExpiredException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Token expired");
    }
}
