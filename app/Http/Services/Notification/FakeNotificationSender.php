<?php
 

namespace App\Http\Services\Notification;

use Illuminate\Http\Client\Response;

class FakeNotificationSender implements NotificationSender
{

    public function send(string $text, string $to_phone_number): Response
    {
        $guzzleResponse = new \GuzzleHttp\Psr7\Response(body: $text);
        return new Response($guzzleResponse);
    }

    public function getServiceName(): string
    {
        return 'fake';
    }
}