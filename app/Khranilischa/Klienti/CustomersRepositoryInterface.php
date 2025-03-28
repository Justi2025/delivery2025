<?php


namespace App\Khranilischa\Klienti;

use App\Khranilischa\Klienti\Dtos\PokupatelProstoObiect;
use App\Khranilischa\Klienti\Dtos\PoiskPokupaliaProstoObiect;

interface CustomersRepositoryInterface
{
    public function poluchitFsekhPokupatelei();

    public function sozdat(PokupatelProstoObiect $customer);

    public function naitiPoUsloviu(PoiskPokupaliaProstoObiect $searchDto);

    public function obnovitKlienta(PokupatelProstoObiect $customerDataDto);

    public function naitiKlientaPoIdentificatoru(int $id): PokupatelProstoObiect;

    public function poluchitPokupateliaPoNomeruZakaza(int $nomer_zakaza): PokupatelProstoObiect;
}