<?php


namespace App\Listeners\Sdelki;

use App\Events\Zakaz\ZakazSozdanSobitie;
use App\Servsi\Telegram\TelegramBotService;
use DateTime;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;

class SlushatelSozdaniaSdelki implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly TelegramBotService $botService
    )
    {
        //
    }

    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(ZakazSozdanSobitie $event): void
    {
        $order = $event->order;
        $creator = $event->user;

        $date = new DateTime($order->getCreatedAt());
        $date_from = $date->format('d.m.Y');

        $order_no = sprintf("%'.05d", $order->getId());

        $status_message = $creator->isCustomer() ? 'ожидает рассмотрения' : 'принят';

        $message = <<<MESSAGE
        Здравствуйте ✋
        
        Заказ #$order_no от $date_from $status_message.
        Адрес ПВЗ: {$order->getShipmentFrom()}
        Адрес доставки: {$order->getShipmentTo()}
        
        MESSAGE;

        if(($chat_id = $event->telegram_chat_id) !== null) {
            $this->botService->sendMessage($chat_id, $message);
        }
        else {
            \Log::log(LogLevel::INFO, "User {$event->user->id} doesnt has telegram chat id");
        }

    }
}
