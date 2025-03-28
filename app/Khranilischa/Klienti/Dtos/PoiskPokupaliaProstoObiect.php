<?php


namespace App\Khranilischa\Klienti\Dtos;

class PoiskPokupaliaProstoObiect
{

    public function __construct(
        public readonly string $term,
        public readonly string $type
    )
    {
    }

    public static function from(array $data): self
    {
        return new self($data['term'], $data['type']);
    }
}