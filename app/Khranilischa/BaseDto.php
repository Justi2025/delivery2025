<?php


namespace App\Khranilischa;

use JsonSerializable;
use ReturnTypeWillChange;

abstract class BaseDto implements JsonSerializable
{
    abstract static function from(array $data): static;

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function toArray(): array
    {
        return json_decode(json_encode(get_class()), true);
    }

    public function __toString()
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
}