<?php
 

namespace App\Khranilischa\Nastroiki;

class BaseSettings
{
    protected function transformSettings(array $initial): array
    {
        $transformedArray = [];

        foreach ($initial as $item) {
            $transformedArray[$item['setting_id']] = $item['value'];
        }

        return $transformedArray;
    }
}