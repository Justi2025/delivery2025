<?php


namespace App\Khranilischa\Authentication;

use App\Khranilischa\Authentication\Token\TokenPair;
use App\Servsi\Authentication\RegistratciaPolzovateliaProstoObjiect;
use Exception;

interface AuthenticationRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function createUser(RegistratciaPolzovateliaProstoObjiect $dto, string $jwt_secret): TokenPair;

    public function getAll();

    /**
     * @throws UserNotFoundException
     * @throws InvalidPasswordException
     */
    public function authenticate(array $credentials, string $jwt_secret): TokenPair;
}