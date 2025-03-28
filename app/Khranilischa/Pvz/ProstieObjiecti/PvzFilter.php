<?php
 

namespace App\Khranilischa\Pvz\ProstieObjiecti;


class PvzFilter
{
    public function __construct(
        private ?string $country
    )
    {
    }

    public static function from(array $data): self
    {
        return new self($data['country'] ?? null);
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return PvzFilter
     */
    public function setCountry(?string $country): PvzFilter
    {
        $this->country = $country;
        return $this;
    }



}