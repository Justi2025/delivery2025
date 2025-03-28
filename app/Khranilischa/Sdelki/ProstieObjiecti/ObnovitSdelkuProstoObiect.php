<?php


namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use App\Common\Primesi\ToArray;
use App\Common\Primesi\ToJson;

class ObnovitSdelkuProstoObiect
{
    use ToJson, ToArray;


    public function __construct(
        public int $order_id,
        public int $customer_debt
    )
    {
    }


    public static function from(array $data): self
    {
        return new self(
            order_id: $data['order_id'],
            customer_debt: $data['customer_debt'] ?? 0
        );
    }
}