<?php
 

namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use App\Khranilischa\Sdelki\Perechislenia\TipMestonaznachenia;
use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Khranilischa\Sdelki\Perechislenia\OrderType1;
use App\Khranilischa\Sdelki\Perechislenia\OrderType2;

class SozdatZakazProstoObiect
{
    private int $clientId;
    private ?int $creatorId;
    private int $fromId;
    private ?int $toId;
    private OrderType1 $type1;
    private OrderType2 $type2;
    private ?string $barcodeImage;
    private ?string $barcodeText;
    private ?string $comment;
    //private ?int $price;
    //private ?int $courierId;
    private TipMestonaznachenia $destinationType;
    private StatusiSdelok $orderStatus;
    private int $pickup_only_paid;
    private ?int $amount_to_pay_for_customer;
    private ?array $barcodeImages = [];


    public function __construct()
    {
    }


    public function toArray(): array
    {
        return [
            'client_id' => $this->clientId,
            'creator_id' => $this->creatorId,
            'status' => $this->orderStatus,
            'shipping_from_id' => $this->fromId,
            'shipping_to_id' => $this->toId,
            'destination_type' => $this->destinationType,
            'type1' => $this->type1,
            'type2' => $this->type2,
            //'price' => $this->price,
            'barcode_image' => $this->barcodeImage,
            'barcode_text' => $this->barcodeText,
            //'courier_id' => $this->courierId,
            'pickup_only_paid' => $this->pickup_only_paid,
            'amount_to_pay_for_customer' => $this->amount_to_pay_for_customer,
        ];
    }

    /**
     * @return TipMestonaznachenia
     */
    public function getDestinationType(): TipMestonaznachenia
    {
        return $this->destinationType;
    }

    /**
     * @param TipMestonaznachenia $destinationType
     * @return SozdatZakazProstoObiect
     */
    public function setDestinationType(TipMestonaznachenia $destinationType): SozdatZakazProstoObiect
    {
        $this->destinationType = $destinationType;
        return $this;
    }


    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    /**
     * @param int|null $creatorId
     * @return SozdatZakazProstoObiect
     */
    public function setCreatorId(?int $creatorId): self
    {
        $this->creatorId = $creatorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromId(): int
    {
        return $this->fromId;
    }

    /**
     * @param int $fromId
     */
    public function setFromId(int $fromId)
    {
        $this->fromId = $fromId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getToId(): ?int
    {
        return $this->toId;
    }

    /**
     * @param int|null $toId
     */
    public function setToId(?int $toId)
    {
        $this->toId = $toId;
        return $this;
    }

    /**
     * @return OrderType1
     */
    public function getType1(): OrderType1
    {
        return $this->type1;
    }

    /**
     * @param OrderType1 $type1
     * @return SozdatZakazProstoObiect
     */
    public function setType1(OrderType1 $type1): SozdatZakazProstoObiect
    {
        $this->type1 = $type1;
        return $this;
    }

    /**
     * @return OrderType2
     */
    public function getType2(): OrderType2
    {
        return $this->type2;
    }

    /**
     * @param OrderType2 $type2
     * @return SozdatZakazProstoObiect
     */
    public function setType2(OrderType2 $type2): SozdatZakazProstoObiect
    {
        $this->type2 = $type2;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getBarcodeImage(): ?string
    {
        return $this->barcodeImage;
    }

    /**
     * @param string|null $barcodeImage
     */
    public function setBarcodeImage(?string $barcodeImage)
    {
        $this->barcodeImage = $barcodeImage;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBarcodeText(): ?string
    {
        return $this->barcodeText;
    }

    /**
     * @param string|null $barcodeText
     */
    public function setBarcodeText(?string $barcodeText)
    {
        $this->barcodeText = $barcodeText;
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
     */
    public function setComment(?string $comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return StatusiSdelok
     */
    public function getOrderStatus(): StatusiSdelok
    {
        return $this->orderStatus;
    }

    /**
     * @param StatusiSdelok $orderStatus
     * @return SozdatZakazProstoObiect
     */
    public function setOrderStatus(StatusiSdelok $orderStatus): static
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    public function getPickupOnlyPaid(): int
    {
        return $this->pickup_only_paid;
    }

    public function setPickupOnlyPaid(int $pickup_only_paid): SozdatZakazProstoObiect
    {
        $this->pickup_only_paid = $pickup_only_paid;
        return $this;
    }

    public function getAmountToPayForCustomer(): ?int
    {
        return $this->amount_to_pay_for_customer;
    }

    public function setAmountToPayForCustomer(?int $amount_to_pay_for_customer): SozdatZakazProstoObiect
    {
        $this->amount_to_pay_for_customer = $amount_to_pay_for_customer;
        return $this;
    }

    public function getBarcodeImages(): ?array
    {
        return $this->barcodeImages;
    }

    public function setBarcodeImages(?array $barcodeImages): SozdatZakazProstoObiect
    {
        $this->barcodeImages = $barcodeImages;
        return $this;
    }


    /**
     * When order created by manager
     *
     * @param array $data
     * @return SozdatZakazProstoObiect
     */
    public static function fromEmployee(array $data): static
    {
        $dto = new static();

        $dto->setClientId($data['client_id'])
            ->setFromId($data['from_id'])
            ->setToId($data['to_id'] ?? null)
            ->setDestinationType(TipMestonaznachenia::from($data['destination_type']))
            //->setPrice($data['price'] ?? null)
            // ->setCourierId($data['courier_id'] ?? null)
            ->setBarcodeImage($data['barcode_image'] ?? null)
            ->setBarcodeText($data['barcode_text'] ?? null)
            ->setComment($data['comment'] ? strip_tags($data['comment']) : null)
            ->setPickupOnlyPaid($data['pickup_only_paid'])
            ->setAmountToPayForCustomer($data['amount_to_pay_for_customer'] ?? null)
            ->setBarcodeImages($data['barcode_images'] ?? null)
        ;

        return $dto;
    }


    /**
     * When order created by customer this data should be arrived from form
     *
     * @param array $data
     * @return static
     */
    public static function fromCustomer(array $data): static
    {
        $dto = new static();

        return $dto
            ->setFromId($data['from_id'])
            ->setToId($data['to_id'] ?? null)
            ->setDestinationType(TipMestonaznachenia::from($data['destination_type']))
            ->setBarcodeImage($data['barcode_image'] ?? null)
            ->setBarcodeText($data['barcode_text'] ?? null)
            ->setComment($data['comment'] ? strip_tags($data['comment']) : null)
            ->setPickupOnlyPaid($data['pickup_only_paid'])
            ->setAmountToPayForCustomer($data['amount_to_pay_for_customer'] ?? null)
            ->setBarcodeImages($data['barcode_images'] ?? null)
        ;
    }


}