<?php


namespace App\Common\Primesi;

trait ToArray
{
    public function toArray(): array
    {
        return json_decode(json_encode(get_object_vars($this)), true);
    }
}