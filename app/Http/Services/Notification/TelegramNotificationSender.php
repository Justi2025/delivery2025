<?php
 

namespace App\Http\Services\Notification;

use App\Khranilischa\User\RepositoriPolzovatelei;
use App\Servsi\Telegram\TelegramBotService;
use Illuminate\Http\Client\Response;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramNotificationSender implements NotificationSender
{

    public function __construct(
        private readonly TelegramBotService     $botService,
        private readonly RepositoriPolzovatelei $usersRepository,
    )
    {
    }

    /**
     * @throws TelegramSDKException
     */
    public function send(string $text, string $to_phone_number): Response
    {
        $chat_id = $this->usersRepository->getTelegramChatId($to_phone_number);
        $message = $this->botService->sendMessage($chat_id, $text);
        $guzzleResponse = new \GuzzleHttp\Psr7\Response(body: $message);
        return new Response($guzzleResponse);
    }

    public function getServiceName(): string
    {
        return 'telegram_bot';
    }
}