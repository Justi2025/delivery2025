<?php
 

namespace App\Listeners\Sdelki;

use App\Common\Primesi\TgBotUtils;
use App\Events\Zakaz\StatusZakazaIzmenenSobitie;
use App\Models\User;
use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Khranilischa\RoliPolzovatelei;
use App\Servsi\Telegram\TelegramBotService;
use DateTime;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlushatelIzmeneniaSdelkiSotrudnik implements ShouldQueue
{
    use TgBotUtils;

    private readonly string $site_url;

    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly TelegramBotService $botService
    )
    {
        $this->site_url = env('APP_URL');
    }


    public function handle(StatusZakazaIzmenenSobitie $event): void
    {
        $order = $event->order;

        $date = new DateTime($order->getCreatedAt());
        $order_date = $date->format('d.m.Y');

        if ($event->orderStatus->getStatusCode() === StatusiSdelok::OjidaiutRassmotrenia) {
            $message = $this->newOrderMessage($order->getId(), $order_date, $order->getShipmentFrom(), $order->getShipmentTo());

            $chat_ids = User::whereIn('role', [RoliPolzovatelei::Ahmad->value, RoliPolzovatelei::ZamAhmada->value])
                ->whereNotNull('telegram_chat_id')
                ->pluck('telegram_chat_id');

            foreach ($chat_ids as $chat_id) {
                if ($message) {
                    $this->botService->sendMessage($chat_id, $message, parse_mode: 'MarkdownV2');
                }

                sleep(1);
            }
        }

        if ($event->orderStatus->getStatusCode() === StatusiSdelok::NaznacheniNaKuriera) {
            $message = $this->orderAssignedToCourierMessage($order->getId(), $order->getShipmentFrom(), $order->getShipmentTo());

            $courier = User::find($event->order->getCourierId());

            if($courier->telegram_chat_id !== null) {
                $this->botService->sendMessage($courier->telegram_chat_id, $message);
            }
        }

    }
}
