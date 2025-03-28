<?php
 

namespace App\Khranilischa\Sdelki\ProstieObjiecti;

use App\Common\Primesi\ToArray;
use App\Common\Primesi\ToJson;
use DateTime;

class DostavkaProstoObjiect
{
    use ToJson, ToArray;

    private ?int $telegram_chat_id = null;

    public function __construct(
        private ?int           $id,
        private ?int           $order_status,
        private ?string        $customer,
        private ?int           $customer_id,
        private ?int           $customer_bonuses, //should be read only
        private ?int           $order_bonuses,
        //private ?string        $customer_phone,
        private ?string        $shipment_from,
        private ?string        $shipment_from_address,
        private ?string        $shipment_from_city,
        private ?string        $shipment_from_company,
        private ?string        $shipment_from_color,
        private ?string        $shipment_from_label_color,
        private ?string        $shipment_to,
        private ?string        $shipment_to_short_name,
        private ?string        $shipment_to_city,
        private ?string        $shipment_to_address,
        private ?string        $shipment_to_company,
        private ?string        $shipment_to_color,
        private ?string        $shipment_to_label_color,
        private ?string        $courier,
        private ?int           $courier_id,
        private ?float         $weight,
        private ?int           $price,
        private ?string        $warehouse_cells,
        private ?int           $amount_paid_cash,
        private ?int           $amount_paid_cashless,
        private ?int           $amount_paid_bonus,
        private ?int           $bonus_balance,
        private ?int           $client_debt,
        private ?int           $amount_to_pay,
        private ?string        $created_at,
        private ?string        $barcode_image,
        private ?string        $barcode_text,
        private ?int           $destination_type,
        private ?string        $received_at,
        private ?string        $issued_at,
        private ?bool          $dp_from_barcode_changing,
        private ?int           $additional_payment,
        private ?bool          $pickup_only_paid,
        private ?int           $amount_to_pay_for_customer,
        public readonly ?array $history,
        public readonly ?array $barcodes,
    )
    {
    }


    public static function create(): self
    {
        return self::from([]);
    }

    public static function from(array $data): self
    {
        $id = $data['order_id'] ?? $data['id'] ?? null;

        return new self(
            id: $id,
            order_status: $data['order_status'] ?? null,
            customer: $data['customer'] ?? null,
            customer_id: $data['customer_id'] ?? null,
            customer_bonuses: $data['customer_bonuses'] ?? 0,
            order_bonuses: $data['order_bonuses'] ?? 0,
            //customer_phone: $data['customer_phone'] ?? null,
            shipment_from: $data['shipment_from'] ?? null,
            shipment_from_address: $data['shipment_from_address'] ?? null,
            shipment_from_city: $data['shipment_from_city'] ?? null,
            shipment_from_company: $data['shipment_from_company'] ?? null,
            shipment_from_color: $data['shipment_from_color'] ?? null,
            shipment_from_label_color: $data['shipment_from_label_color'] ?? null,
            shipment_to: $data['shipment_to'] ?? null,
            shipment_to_short_name: $data['shipment_to_short_name'] ?? null,
            shipment_to_city: $data['shipment_to_city'] ?? null,
            shipment_to_address: $data['shipment_to_address'] ?? null,
            shipment_to_company: $data['shipment_to_company'] ?? null,
            shipment_to_color: $data['shipment_to_color'] ?? null,
            shipment_to_label_color: $data['shipment_to_label_color'] ?? null,
            courier: $data['courier'] ?? null,
            courier_id: $data['courier_id'] ?? null,
            weight: $data['weight'] ?? null,
            price: $data['price'] ?? null,
            warehouse_cells: $data['warehouse_cells'] ?? null,
            amount_paid_cash: $data['amount_paid_cash'] ?? null,
            amount_paid_cashless: $data['amount_paid_cashless'] ?? null,
            amount_paid_bonus: $data['amount_paid_bonus'] ?? null,
            bonus_balance: $data['customer_bonuses'] ?? null,
            client_debt: $data['client_debt'] ?? null,
            amount_to_pay: $data['amount_to_pay'] ?? null,
            // created_at: DateTime::createFromFormat('Y-m-d H:i:s', $data['created_at']),
            created_at: $data['created_at'] ?? null,
            barcode_image: $data['barcode_image'] ?? null,
            barcode_text: $data['barcode_text'] ?? null,
            destination_type: $data['destination_type'] ?? null,
            received_at: $data['received_at'] ?? null,
            issued_at: $data['issued_at'] ?? null,
            dp_from_barcode_changing: $data['dp_from_barcode_changing'] ?? false,
            additional_payment: $data['additional_payment'] ?? null,
            pickup_only_paid: $data['pickup_only_paid'] ?? null,
            amount_to_pay_for_customer: $data['amount_to_pay_for_customer'] ?? null,
            history: $data['history'] ?? null,
            barcodes: $data['barcodes'] ?? null
        );
    }


    public function __toString(): string
    {
        return $this->toJson($this);
    }

    public function getTelegramChatId(): ?int
    {
        return $this->telegram_chat_id;
    }

    public function setTelegramChatId(?int $telegram_chat_id): DostavkaProstoObjiect
    {
        $this->telegram_chat_id = $telegram_chat_id;
        return $this;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function isDpFromBarcodeChanging(): ?bool
    {
        return $this->dp_from_barcode_changing;
    }

    public function setDpFromBarcodeChanging(?bool $dp_from_barcode_changing): DostavkaProstoObjiect
    {
        $this->dp_from_barcode_changing = $dp_from_barcode_changing;
        return $this;
    }


    /**
     * @return int|null
     */
    public function getOrderStatus(): ?int
    {
        return $this->order_status;
    }

    /**
     * @param int|null $order_status
     * @return self
     */
    public function setOrderStatus(?int $order_status): self
    {
        $this->order_status = $order_status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    /**
     * @param string|null $customer
     * @return self
     */
    public function setCustomer(?string $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    /**
     * @param int|null $customer_id
     * @return self
     */
    public function setCustomerId(?int $customer_id): self
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    public function getCustomerBonuses(): ?int
    {
        return $this->customer_bonuses;
    }

    public function setCustomerBonuses(?int $customer_bonuses): DostavkaProstoObjiect
    {
        $this->customer_bonuses = $customer_bonuses;
        return $this;
    }

    public function getOrderBonuses(): ?int
    {
        return $this->order_bonuses;
    }

    public function setOrderBonuses(?int $order_bonuses): DostavkaProstoObjiect
    {
        $this->order_bonuses = $order_bonuses;
        return $this;
    }

    /*public function getCustomerPhone(): ?string
    {
        return $this->customer_phone;
    }

    public function setCustomerPhone(?string $customer_phone): IncomingOrderDto
    {
        $this->customer_phone = $customer_phone;
        return $this;
    }*/

    /**
     * @return string|null
     */
    public function getShipmentFrom(): ?string
    {
        return $this->shipment_from;
    }

    /**
     * @param string|null $shipment_from
     * @return self
     */
    public function setShipmentFrom(?string $shipment_from): self
    {
        $this->shipment_from = $shipment_from;
        return $this;
    }

    public function getShipmentFromColor(): ?string
    {
        return $this->shipment_from_color;
    }

    public function setShipmentFromColor(?string $shipment_from_color): DostavkaProstoObjiect
    {
        $this->shipment_from_color = $shipment_from_color;
        return $this;
    }

    public function getShipmentFromLabelColor(): ?string
    {
        return $this->shipment_from_label_color;
    }

    public function setShipmentFromLabelColor(?string $shipment_from_label_color): DostavkaProstoObjiect
    {
        $this->shipment_from_label_color = $shipment_from_label_color;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getShipmentTo(): ?string
    {
        return $this->shipment_to;
    }

    /**
     * @param string|null $shipment_to
     * @return self
     */
    public function setShipmentTo(?string $shipment_to): self
    {
        $this->shipment_to = $shipment_to;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCourier(): ?string
    {
        return $this->courier;
    }

    /**
     * @param string|null $courier
     * @return self
     */
    public function setCourier(?string $courier): self
    {
        $this->courier = $courier;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCourierId(): ?int
    {
        return $this->courier_id;
    }

    /**
     * @param int|null $courier_id
     * @return self
     */
    public function setCourierId(?int $courier_id): self
    {
        $this->courier_id = $courier_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     * @return self
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWarehouseCells(): ?string
    {
        return $this->warehouse_cells;
    }

    /**
     * @param string|null $warehouse_cells
     * @return self
     */
    public function setWarehouseCells(?string $warehouse_cells): self
    {
        $this->warehouse_cells = $warehouse_cells;
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
     * @return self
     */
    public function setClientDebt(?int $client_debt): self
    {
        $this->client_debt = $client_debt;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmountToPay(): ?int
    {
        return $this->amount_to_pay;
    }

    /**
     * @param int|null $amount_to_pay
     * @return self
     */
    public function setAmountToPay(?int $amount_to_pay): self
    {
        $this->amount_to_pay = $amount_to_pay;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @param string|null $created_at
     * @return self
     */
    public function setCreatedAt(?string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBarcodeImage(): ?string
    {
        return $this->barcode_image;
    }

    /**
     * @param string|null $barcode_image
     * @return self
     */
    public function setBarcodeImage(?string $barcode_image): self
    {
        $this->barcode_image = $barcode_image;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBarcodeText(): ?string
    {
        return $this->barcode_text;
    }

    /**
     * @param string|null $barcode_text
     * @return self
     */
    public function setBarcodeText(?string $barcode_text): self
    {
        $this->barcode_text = $barcode_text;
        return $this;
    }

    public function getDestinationType(): ?int
    {
        return $this->destination_type;
    }

    public function setDestinationType(?int $destination_type): DostavkaProstoObjiect
    {
        $this->destination_type = $destination_type;
        return $this;
    }


    /**
     * @return DateTime|null
     */
    public function getReceivedAt(): ?string
    {
        return $this->received_at;
    }

    /**
     * @param string|null $received_at
     * @return self
     */
    public function setReceivedAt(?string $received_at): self
    {
        $this->received_at = $received_at;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getIssuedAt(): ?string
    {
        return $this->issued_at;
    }

    /**
     * @param string|null $issued_at
     * @return self
     */
    public function setIssuedAt(?string $issued_at): self
    {
        $this->issued_at = $issued_at;
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
     * @return self
     */
    public function setAmountPaidCash(?int $amount_paid_cash): self
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
     * @return self
     */
    public function setAmountPaidCashless(?int $amount_paid_cashless): self
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
     * @return self
     */
    public function setAmountPaidBonus(?int $amount_paid_bonus): self
    {
        $this->amount_paid_bonus = $amount_paid_bonus;
        return $this;
    }

    public function getBonusBalance(): ?int
    {
        return $this->bonus_balance;
    }

    public function setBonusBalance(?int $bonus_balance): DostavkaProstoObjiect
    {
        $this->bonus_balance = $bonus_balance;
        return $this;
    }

    public function getShipmentFromAddress(): ?string
    {
        return $this->shipment_from_address;
    }

    public function setShipmentFromAddress(?string $shipment_from_address): DostavkaProstoObjiect
    {
        $this->shipment_from_address = $shipment_from_address;
        return $this;
    }

    public function getShipmentFromCity(): ?string
    {
        return $this->shipment_from_city;
    }

    public function setShipmentFromCity(?string $shipment_from_city): DostavkaProstoObjiect
    {
        $this->shipment_from_city = $shipment_from_city;
        return $this;
    }

    public function getShipmentFromCompany(): ?string
    {
        return $this->shipment_from_company;
    }

    public function setShipmentFromCompany(?string $shipment_from_company): DostavkaProstoObjiect
    {
        $this->shipment_from_company = $shipment_from_company;
        return $this;
    }

    public function getShipmentToShortName(): ?string
    {
        return $this->shipment_to_short_name;
    }

    public function setShipmentToShortName(?string $shipment_to_short_name): DostavkaProstoObjiect
    {
        $this->shipment_to_short_name = $shipment_to_short_name;
        return $this;
    }

    public function getShipmentToCity(): ?string
    {
        return $this->shipment_to_city;
    }

    public function setShipmentToCity(?string $shipment_to_city): DostavkaProstoObjiect
    {
        $this->shipment_to_city = $shipment_to_city;
        return $this;
    }

    public function getShipmentToAddress(): ?string
    {
        return $this->shipment_to_address;
    }

    public function setShipmentToAddress(?string $shipment_to_address): DostavkaProstoObjiect
    {
        $this->shipment_to_address = $shipment_to_address;
        return $this;
    }

    public function getShipmentToCompany(): ?string
    {
        return $this->shipment_to_company;
    }

    public function setShipmentToCompany(?string $shipment_to_company): DostavkaProstoObjiect
    {
        $this->shipment_to_company = $shipment_to_company;
        return $this;
    }

    public function getShipmentToColor(): ?string
    {
        return $this->shipment_to_color;
    }

    public function setShipmentToColor(?string $shipment_to_color): DostavkaProstoObjiect
    {
        $this->shipment_to_color = $shipment_to_color;
        return $this;
    }

    public function getShipmentToLabelColor(): ?string
    {
        return $this->shipment_to_label_color;
    }

    public function setShipmentToLabelColor(?string $shipment_to_label_color): DostavkaProstoObjiect
    {
        $this->shipment_to_label_color = $shipment_to_label_color;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): DostavkaProstoObjiect
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     *
     * Additional payment for dp
     *
     * @return int|null
     */
    public function getAdditionalPayment(): ?int
    {
        return $this->additional_payment;
    }

    public function setAdditionalPayment(?int $additional_payment): DostavkaProstoObjiect
    {
        $this->additional_payment = $additional_payment;
        return $this;
    }

    public function getPickupOnlyPaid(): ?bool
    {
        return $this->pickup_only_paid;
    }

    public function setPickupOnlyPaid(?bool $pickup_only_paid): DostavkaProstoObjiect
    {
        $this->pickup_only_paid = $pickup_only_paid;
        return $this;
    }

    public function getAmountToPayForCustomer(): ?int
    {
        return $this->amount_to_pay_for_customer;
    }

    public function setAmountToPayForCustomer(?int $amount_to_pay_for_customer): DostavkaProstoObjiect
    {
        $this->amount_to_pay_for_customer = $amount_to_pay_for_customer;
        return $this;
    }

}