<?php
 

namespace App\Khranilischa\Sdelki;

use App\Khranilischa\Klienti\Dtos\PokupatelProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\DostavkaProstoObjiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\ObnovitSdelkuProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\SozdatZakazProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\ProstoiObiectZakaza;
use App\Khranilischa\Sdelki\ProstieObjiecti\FiltrZakazovProsto;
use App\Khranilischa\Sdelki\ProstieObjiecti\StatusZakazaProstoObiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\SdelkiNaKurieraProstoObjiect;
use App\Khranilischa\Sdelki\ProstieObjiecti\ProstoiObiectSortirovkiZakaza;
use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Servsi\Sdelki\Prosto\VidatZakazProstoObiect;

interface InyterfaceRepositoriaSdelok
{
    public function poluchitOdinZakaz(int $page = 0, int $perPage = 0, ?ProstoiObiectSortirovkiZakaza $optcii_sortirovki = null, ?FiltrZakazovProsto $filtry = null, ?StatusiSdelok $status_zakaza = null);

    public function polishitFseSdelki(?FiltrZakazovProsto $filters = null);

    public function poluchitZakazDliaPokupatelia(int $customer_id);

    public function sozdatZakaz(SozdatZakazProstoObiect $dto): ?DostavkaProstoObjiect;

    public function poluchitSdelku(int $id, bool $s_istoriei = true, ?int $user_id = null): DostavkaProstoObjiect;

    public function naznachitCurieru(SdelkiNaKurieraProstoObjiect $dto): bool;

    public function izmenitStatusZakaza(StatusZakazaProstoObiect $dto, DostavkaProstoObjiect $incomingOrderDto): ?DostavkaProstoObjiect;

    public function obnovitShtrikhKodSdelki(DostavkaProstoObjiect $incomingOrder): bool;

    public function poluchitSgrupirovanniePoPvzId(?FiltrZakazovProsto $filtry = null);

    public function poluchitPokupateliaPoIdentificatoruZakaza(int $order_id): PokupatelProstoObiect;

    public function poluchitNomerTelephona(int $order_id);

    public function prinadlejitLiZakazPokupateliu(int $user_id, int $order_id): bool;

    public function obnovitSdelku(ObnovitSdelkuProstoObiect $dto);

    public function otmenitSdelku(ProstieObjiecti\OtmenitSdelkuProsoObjiect $dto);

    public function poluchitSdelkuPoIdentificatoru(int $order_id): ProstoiObiectZakaza;

    public function vernutSdelkuVPrinatie(int $order_id, int $courier_id): ?bool;

    public function izmenitStatusSdelkiNa(ProstieObjiecti\IzmenitStatusSdelkiNaProstoObiect $status);

    public function vidatZakaz(VidatZakazProstoObiect $dto): ?DostavkaProstoObjiect;
}