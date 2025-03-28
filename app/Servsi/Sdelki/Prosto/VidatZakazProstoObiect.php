<?php


namespace App\Servsi\Sdelki\Prosto;

use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Servsi\Perechislenia\SposobPlateja;

class VidatZakazProstoObiect
{
    private int $amount_paid = 0;
    private int $creator_id;


    public function __construct(
        public readonly int $order_id,
        public readonly SposobPlateja $payment_type
    )
    {
    }


    public static function from(array $data): self
    {
        return new self(
            order_id: $data['order_id'],
            payment_type: SposobPlateja::from($data['payment_type'])
        );
    }


    public function toOrderArray(): array
    {
        $fields = [
            'id' => $this->order_id,
            'status' => StatusiSdelok::Vidan,
            'issued_at' => now()
        ];

        if($this->payment_type === SposobPlateja::Cash) {
            $fields['amount_paid_cash'] = $this->getAmountPaid();
        }

        if($this->payment_type === SposobPlateja::Cashless) {
            $fields['amount_paid_cashless'] = $this->getAmountPaid();
        }

        return $fields;
    }

    public function getAmountPaid(): int
    {
        return $this->amount_paid;
    }

    public function setAmountPaid(int $amount_paid): VidatZakazProstoObiect
    {
        $this->amount_paid = $amount_paid;
        return $this;
    }

    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    public function setCreatorId(int $creator_id): VidatZakazProstoObiect
    {
        $this->creator_id = $creator_id;
        return $this;
    }
}