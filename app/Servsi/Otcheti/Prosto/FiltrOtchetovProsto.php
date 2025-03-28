<?php
 

namespace App\Servsi\Otcheti\Prosto;

use App\Common\Primesi\GetDate;
use DateTime;

class FiltrOtchetovProsto
{
    use GetDate;

    public function __construct(
        public readonly ?DateTime  $day = null,
        public readonly ?int       $office = null,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            day: self::getDate($data['day'] ?? null),
            office: $data['office'] ?? null
        );
    }
}