<?php


namespace App\Servsi\Authentication;

use App\Khranilischa\RoliPolzovatelei;
use App\Khranilischa\UserStatus;
use DateTime;

class RegistratciaPolzovateliaProstoObjiect
{
    /*private string $lastname;
    private string $firstname;
    private ?string $middlename;*/
    private string $full_name;
    private int $city_id;
    private int $yearOfBirth;
    private ?string $street;
    private ?string $house;
    private ?string $flat;
    private string $phone;
    private string $password;
    private ?string $code;

    private int $role;
    private int $status;
    private ?string $avatar;

    private ?int $creator_id;
    private bool $is_vip;
    private ?int $telegram_chat_id;
    private ?DateTime $phone_verified_at;


    public static function from(array $data): static
    {
        $obj = new static();
        $obj->full_name = preg_replace('/\s+/', ' ', $data['full_name']); // remove extra spaces
        $obj->city_id = $data['city_id'];
        $obj->yearOfBirth = $data['year_of_birth'];
        $obj->street = $data['street'] ?? null;
        $obj->house = $data['house'] ?? null;
        $obj->flat = $data['flat'] ?? null;
        $obj->phone = $data['phone'];
        $obj->password = $data['password'];
        $obj->code = $data['code'] ?? null;

        $obj->role = $data['role'];
        $obj->status = $data['status'];
        $obj->avatar = $data['avatar'] ?? null;
        $obj->creator_id = $data['creator_id'] ?? null;
        $obj->is_vip = $data['is_vip'] ?? false;
        $obj->telegram_chat_id = $data['telegram_chat_id'] ?? null;
        $obj->phone_verified_at = $data['phone_verified_at']?? null;

        return $obj;
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->full_name,
            'city_id' => $this->city_id,
            'year_of_birth' => $this->yearOfBirth,
            'street' => $this->street,
            'house' => $this->house,
            'flat' => $this->flat,
            'phone' => $this->phone,
            'password' => $this->password,
            'phone_verified_at' => $this->phone_verified_at,
            'telegram_chat_id' => $this->telegram_chat_id,

            'role' => $this->role,
            'status' => $this->status,
        ];
    }


    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @param string $full_name
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setFullName(string $full_name): RegistratciaPolzovateliaProstoObjiect
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->city_id;
    }

    /**
     * @param int $city_id
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setCityId(int $city_id): static
    {
        $this->city_id = $city_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getYearOfBirth(): int
    {
        return $this->yearOfBirth;
    }

    /**
     * @param int $yearOfBirth
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setYearOfBirth(int $yearOfBirth): static
    {
        $this->yearOfBirth = $yearOfBirth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setStreet(?string $street): static
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHouse(): ?string
    {
        return $this->house;
    }

    /**
     * @param string|null $house
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setHouse(?string $house): static
    {
        $this->house = $house;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFlat(): ?string
    {
        return $this->flat;
    }

    /**
     * @param string|null $flat
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setFlat(?string $flat): static
    {
        $this->flat = $flat;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setCode(?string $code): RegistratciaPolzovateliaProstoObjiect
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param RoliPolzovatelei $role
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setRole(RoliPolzovatelei $role): static
    {
        $this->role = $role->value;
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
     * @param UserStatus $status
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setStatus(UserStatus $status): static
    {
        $this->status = $status->value;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;
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
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setCreatorId(?int $creator_id): static
    {
        $this->creator_id = $creator_id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsVip(): bool
    {
        return $this->is_vip;
    }

    /**
     * @param bool $is_vip
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setIsVip(bool $is_vip): static
    {
        $this->is_vip = $is_vip;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTelegramChatId(): ?int
    {
        return $this->telegram_chat_id;
    }

    /**
     * @param int|null $telegram_chat_id
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setTelegramChatId(?int $telegram_chat_id): static
    {
        $this->telegram_chat_id = $telegram_chat_id;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getPhoneVerifiedAt(): ?DateTime
    {
        return $this->phone_verified_at;
    }

    /**
     * @param DateTime|null $phone_verified_at
     * @return RegistratciaPolzovateliaProstoObjiect
     */
    public function setPhoneVerifiedAt(?DateTime $phone_verified_at): static
    {
        $this->phone_verified_at = $phone_verified_at;
        return $this;
    }


}