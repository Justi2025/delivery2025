<?php


namespace App\Http\Services\Notification;

use Illuminate\Http\Client\Response;

interface NotificationSender
{
    public function send(string $text, string $to_phone_number): Response;

    public function getServiceName(): string;
}