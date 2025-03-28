<?php
 

namespace App\Khranilischa\Sdelki\Perechislenia;

enum StatusiSdelok : int
{
    use EnumCasesToString;

    case Undefined = 0;
    case OjidaiutRassmotrenia = 1;
    case Prinati = 2;
    case NaznacheniNaKuriera = 3;
    case PolucheniKurierom = 4;
    case OjidautPokupatelia = 5;
    case Vidan = 6;
    case VidanOplachenChastichno = 7;
    case Otmenen = 8;
    case Otlojen = 9;
    case DolgPokupateliaZakrit = 10;

    public function nameRu(): string
    {
        return match ($this) {
            self::OjidaiutRassmotrenia => 'Ожидает рассмотрения',
            self::Prinati => 'Принят',
            self::NaznacheniNaKuriera => 'Назначен курьеру',
            self::PolucheniKurierom => 'Получен курьером',
            self::OjidautPokupatelia => 'Готов к выдаче',
            self::VidanOplachenChastichno => 'Выдан, оплачен частично',
            self::DolgPokupateliaZakrit => 'Долг оплачен',
            self::Vidan => 'Выдан',
            self::Otmenen => 'Отменен',
            self::Otlojen => 'Отложен',
            self::Undefined => 'Статус неизвестен',
        };
    }
}