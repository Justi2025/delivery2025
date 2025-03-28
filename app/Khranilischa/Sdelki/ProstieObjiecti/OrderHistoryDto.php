<?php


namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use DateTime;

class OrderHistoryDto
{

    public function __construct(
        private ?int $id,
        private ?int $order_id,
        private ?int $status,
        private ?string $comment,
        private ?int $creator_id,
        private ?DateTime $created_at,
        private ?DateTime $updated_at,
        private ?string $full_name,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            order_id: $data['order_id'] ?? null,
            status: $data['status'] ?? null,
            comment: $data['comment'] ?? null,
            creator_id: $data['creator_id'] ?? null,
            created_at: isset($data['created_at']) ? new DateTime($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? new DateTime($data['updated_at']) : null,
            full_name: $data['full_name'],
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return OrderHistoryDto
     */
    public function setId(int $id): OrderHistoryDto
    {
        $this->id = $id;
        return $this;
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
     * @return OrderHistoryDto
     */
    public function setOrderId(int $order_id): OrderHistoryDto
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return OrderHistoryDto
     */
    public function setStatus(int $status): OrderHistoryDto
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return OrderHistoryDto
     */
    public function setComment(string $comment): OrderHistoryDto
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreatorId(): int
    {
        return $this->creator_id;
    }

    /**
     * @param int $creator_id
     * @return OrderHistoryDto
     */
    public function setCreatorId(int $creator_id): OrderHistoryDto
    {
        $this->creator_id = $creator_id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $created_at
     * @return OrderHistoryDto
     */
    public function setCreatedAt(DateTime $created_at): OrderHistoryDto
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param DateTime $updated_at
     * @return OrderHistoryDto
     */
    public function setUpdatedAt(DateTime $updated_at): OrderHistoryDto
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    /**
     * @param string|null $full_name
     * @return OrderHistoryDto
     */
    public function setFullName(?string $full_name): OrderHistoryDto
    {
        $this->full_name = $full_name;
        return $this;
    }


}