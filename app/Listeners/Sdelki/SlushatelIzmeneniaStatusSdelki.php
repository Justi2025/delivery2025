<?php
 

namespace App\Listeners\Sdelki;

use App\Common\Primesi\TgBotUtils;
use App\Common\Primesi\ToJson;
use App\Events\Zakaz\StatusZakazaIzmenenSobitie;
use App\Khranilischa\Sdelki\Perechislenia\TipMestonaznachenia;
use App\Khranilischa\Sdelki\Perechislenia\StatusiSdelok;
use App\Servsi\Telegram\TelegramBotService;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Psr\Log\LogLevel;

class SlushatelIzmeneniaStatusSdelki implements ShouldQueue
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


    public function acceptedMessage(
        string  $order_id,
        string  $order_date,
        ?string $dp_from,
    ): string
    {
        $order_no = $this->formatOrderId($order_id);

        $message = <<<MESSAGE
        Здравствуйте!
        
        Заказ #$order_no от $order_date из $dp_from принят в работу
            
        MESSAGE;

        return $this->escapeTgChar($message);
    }

    use ToJson;

    /**
     * Handle the event.
     * @throws \Exception
     */
    public function handle(StatusZakazaIzmenenSobitie $event): void
    {
        $order = $event->order;
        $orderStatus = $event->orderStatus;

        $date = new DateTime($order->getCreatedAt());
        $order_date = $date->format('d.m.Y');

        // $order_no = sprintf("%'.05d", $order->getId());
        // $order_status = mb_strtolower($this->statusName($order->getOrderStatus()));
        // $order_url = sprintf('%s/incomings/%d', env('APP_URL'), $order->getId());

        $message = '';

        $dp_from = sprintf(
            "%s, %s, %s",
            $order->getShipmentFromCompany(),
            $order->getShipmentFromCity(),
            $order->getShipmentFromAddress()
        );

        if ($orderStatus->getStatusCode() === StatusiSdelok::OjidaiutRassmotrenia) {
            $message = $this->waitingMessage(
                $order->getId(),
                $order_date,
                $dp_from
            );
        }

        if ($orderStatus->getStatusCode() === StatusiSdelok::Prinati) {
            $message = $this->acceptedMessage(
                $order->getId(),
                $order_date,
                $dp_from
            );
        }



        if ($orderStatus->getStatusCode() === StatusiSdelok::OjidautPokupatelia) {

            Log::log(LogLevel::DEBUG, $this->toJson($order->getDestinationType()));

//            if($orderStatus->getDestinationType() !== DestinationType::DeliveryPoint) {
//                // Todo: what message should be send to customer if destination type is customer home or customer address?
//                return;
//            }

            $message = $this->waitingForCustomerMessage(
                $order->getId(),
                $order_date,
                $dp_from,
                $order->getShipmentTo(),
                $order->getPrice() + $order->getAdditionalPayment() + $order->getClientDebt()
            );
        }

        if ($orderStatus->getStatusCode() === StatusiSdelok::Vidan) {
            $message = $this->issuedMessage(
                $order->getId(),
                $order_date,
                $dp_from,
                $order->getAmountToPay() + $order->getAdditionalPayment()
            );
        }


        if ($orderStatus->getStatusCode() === StatusiSdelok::VidanOplachenChastichno) {

            $totalPaid = $order->getAmountPaidCash() + $order->getAmountPaidBonus() + $order->getAmountPaidCashless();
            $remaining = ($order->getPrice() + $order->getAdditionalPayment() + $order->getClientDebt()) - $totalPaid;

            $message = $this->issuedPartiallyPaid(
                $order->getId(),
                $order_date,
                $dp_from,
                $totalPaid,
                $remaining
            );
        }

        if (($chat_id = $event->telegram_chat_id) !== null) {
            if ($message)
            {
                $message_out = <<<MESSAGEOUT
                $message
                [Посмотреть заказ]($this->site_url/incomings/{$order->getId()})
                MESSAGEOUT;

                $this->botService->sendMessage($chat_id, $message_out, parse_mode: 'MarkdownV2');
            }
        } else {
            Log::log(LogLevel::INFO, "User {$event->user->id} doesnt has telegram chat id");
        }
    }
}
