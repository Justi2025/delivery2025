<?php


namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;

class IzmenitStatusSdelkiNaProstoObiect
{

    public function __construct(
        public readonly int $orderId,
        public readonly StatusiSdelok $orderStatus
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            orderId: $data['order_id'],
            orderStatus: StatusiSdelok::from($data['order_status'])
        );
    }


    public function toArray(): array
    {
        return  [
            'id' => $this->orderId,
            'status' => $this->orderStatus
        ];
    }
}