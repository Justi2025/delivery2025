<?php
 

namespace App\Khranilischa\Pvz\ProstieObjiecti;

class PvzPO
{
    public function __construct(
        private string  $country,
        private string  $city,
        private string  $street,
        private string  $house,
        private int     $company_id,
        private string  $short_name,
        private bool    $is_barcode_changing,
        private ?int    $additional_payment,
        private ?int    $usage_frequency,
        public ?int     $creator_id,
        private ?string $color,
        private ?string $label_color,
        private ?string $comment,
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            country: $data['country'],
            city: $data['city'],
            street: $data['street'],
            house: $data['house'],
            company_id: $data['company_id'],
            short_name: $data['short_name'],
            is_barcode_changing: $data['is_barcode_changing'],
            additional_payment: $data['additional_payment'],
            usage_frequency: $data['usage_frequency'] ?? 1,
            creator_id: $data['creator_id'] ?? 0,
            color: $data['color'],
            label_color: $data['label_color'] ?? '#000',
            comment: $data['comment']
        );
    }

    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'city' => $this->city,
            'street' => $this->street,
            'house' => $this->house,
            'name' => 'OLD_VALUE',
            'company_id' => $this->company_id,
            'short_name' => $this->short_name,
            'color' => $this->color,
            'label_color' => $this->label_color,
            'comment' => $this->comment,
            'is_barcode_changing' => $this->is_barcode_changing,
            'additional_payment' => $this->additional_payment,
            'usage_frequency' => $this->usage_frequency,
            'creator_id' => $this->creator_id
        ];
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return PvzPO
     */
    public function setCountry(string $country): PvzPO
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return PvzPO
     */
    public function setCity(string $city): PvzPO
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return PvzPO
     */
    public function setStreet(string $street): PvzPO
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouse(): string
    {
        return $this->house;
    }

    /**
     * @param string $house
     * @return PvzPO
     */
    public function setHouse(string $house): PvzPO
    {
        $this->house = $house;
        return $this;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    /**
     * @param int $company_id
     * @return PvzPO
     */
    public function setCompanyId(int $company_id): PvzPO
    {
        $this->company_id = $company_id;
        return $this;
    }

    public function getShortName(): string
    {
        return $this->short_name;
    }

    public function setShortName(string $short_name): PvzPO
    {
        $this->short_name = $short_name;
        return $this;
    }


    /**
     * @return bool
     */
    public function isIsBarcodeChanging(): bool
    {
        return $this->is_barcode_changing;
    }

    /**
     * @param bool $is_barcode_changing
     * @return PvzPO
     */
    public function setIsBarcodeChanging(bool $is_barcode_changing): PvzPO
    {
        $this->is_barcode_changing = $is_barcode_changing;
        return $this;
    }

    public function getAdditionalPayment(): ?int
    {
        return $this->additional_payment;
    }

    public function setAdditionalPayment(?int $additional_payment): PvzPO
    {
        $this->additional_payment = $additional_payment;
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
     * @return PvzPO
     */
    public function setCreatorId(int $creator_id): PvzPO
    {
        $this->creator_id = $creator_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return PvzPO
     */
    public function setColor(?string $color): PvzPO
    {
        $this->color = $color;
        return $this;
    }

    public function getLabelColor(): ?string
    {
        return $this->label_color;
    }

    public function setLabelColor(?string $label_color): PvzPO
    {
        $this->label_color = $label_color;
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
     * @return PvzPO
     */
    public function setComment(?string $comment): PvzPO
    {
        $this->comment = $comment;
        return $this;
    }

}