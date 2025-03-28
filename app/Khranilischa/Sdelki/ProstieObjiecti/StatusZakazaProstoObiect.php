<?php
 

namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;

class StatusZakazaProstoObiect
{
    public function __construct(
        private int            $order_id,
        private StatusiSdelok  $status_code,
        private ?StatusiSdelok $old_status,
        private ?int           $creator_id,
        private ?string        $comment,
        private ?int           $amount_paid_cash = 0, // Todo: this can lead to error, as same fields defined in IncomingOrderDto dto
        private ?int           $amount_paid_cashless = 0,
        private ?int           $amount_paid_bonus = 0,
        private ?array         $photos = []
    )
    {
    }

    public static function from(array $data): static
    {
        return new static(
            order_id: $data['order_id'],
            status_code: StatusiSdelok::from($data['status_code']),
            old_status: isset($data['old_status']) ? StatusiSdelok::from($data['old_status']) : null,
            creator_id: $data['creator_id'] ?? null,
            comment: $data['comment'] ?? null,
            amount_paid_cash: $data['amount_paid_cash'] ?? 0,
            amount_paid_cashless: $data['amount_paid_cashless'] ?? 0,
            amount_paid_bonus: $data['amount_paid_bonus'] ?? 0,
            photos: $data['photos'] ?? null,
        );
    }


    public static function create(int $order_id, int $status_code): static
    {
        return new static($order_id, StatusiSdelok::from($status_code), null, null, null);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'status' => $this->status_code,
            'creator_id' => $this->creator_id,
            'comment' => $this->comment,
        ];
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     * @return StatusZakazaProstoObiect
     */
    public function setOrderId(int $order_id): StatusZakazaProstoObiect
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @return StatusiSdelok
     */
    public function getStatusCode(): StatusiSdelok
    {
        return $this->status_code;
    }

    /**
     * @param StatusiSdelok $status_code
     * @return StatusZakazaProstoObiect
     */
    public function setStatusCode(StatusiSdelok $status_code): StatusZakazaProstoObiect
    {
        $this->status_code = $status_code;
        return $this;
    }

    public function getOldStatus(): ?StatusiSdelok
    {
        return $this->old_status;
    }

    public function setOldStatus(?StatusiSdelok $old_status): StatusZakazaProstoObiect
    {
        $this->old_status = $old_status;
        return $this;
    }


    /**
     * @return int|null
     */
    public function getCreatorId(): ?int
    {
        return $this->creator_id;
    }

    /**
     * @param int|null $creator_id
     * @return StatusZakazaProstoObiect
     */
    public function setCreatorId(?int $creator_id): StatusZakazaProstoObiect
    {
        $this->creator_id = $creator_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return StatusZakazaProstoObiect
     */
    public function setComment(?string $comment): StatusZakazaProstoObiect
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getClientDebt(): ?int
    {
        return $this->client_debt;
    }

    /**
     * @param int|null $client_debt
     * @return StatusZakazaProstoObiect
     */
    public function setClientDebt(?int $client_debt): StatusZakazaProstoObiect
    {
        $this->client_debt = $client_debt;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getServicePrice(): ?int
    {
        return $this->service_price;
    }

    /**
     * @param int|null $service_price
     * @return StatusZakazaProstoObiect
     */
    public function setServicePrice(?int $service_price): StatusZakazaProstoObiect
    {
        $this->service_price = $service_price;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountPaidCash(): ?int
    {
        return $this->amount_paid_cash;
    }

    /**
     * @param int|null $amount_paid_cash
     * @return StatusZakazaProstoObiect
     */
    public function setAmountPaidCash(?int $amount_paid_cash): StatusZakazaProstoObiect
    {
        $this->amount_paid_cash = $amount_paid_cash;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountPaidCashless(): ?int
    {
        return $this->amount_paid_cashless;
    }

    /**
     * @param int|null $amount_paid_cashless
     * @return StatusZakazaProstoObiect
     */
    public function setAmountPaidCashless(?int $amount_paid_cashless): StatusZakazaProstoObiect
    {
        $this->amount_paid_cashless = $amount_paid_cashless;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountPaidBonus(): ?int
    {
        return $this->amount_paid_bonus;
    }

    /**
     * @param int|null $amount_paid_bonus
     * @return StatusZakazaProstoObiect
     */
    public function setAmountPaidBonus(?int $amount_paid_bonus): StatusZakazaProstoObiect
    {
        $this->amount_paid_bonus = $amount_paid_bonus;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    /**
     * @param array|null $photos
     * @return StatusZakazaProstoObiect
     */
    public function setPhotos(?array $photos): StatusZakazaProstoObiect
    {
        $this->photos = $photos;
        return $this;
    }


}