<?php
 

namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use App\Common\Primesi\ToArray;

class SdelkiNaKurieraProstoObjiect
{
    use ToArray;

    private string $comment = '';
    private int $creator_id;

    public function __construct(
        public readonly int   $courier_id,
        public readonly array $order_ids,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            courier_id: $data['courier_id'],
            order_ids: $data['order_ids']
        );
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): SdelkiNaKurieraProstoObjiect
    {
        $this->comment = $comment;
        return $this;
    }

    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    public function setCreatorId(int $creator_id): SdelkiNaKurieraProstoObjiect
    {
        $this->creator_id = $creator_id;
        return $this;
    }


}