<?php
 

namespace App\Http\Controllers;

use App\Servsi\Telegram\TelegramBotService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;
use Telegram\Bot\Api as TelegramBotApi;

class GlavniPoTelegramBotu extends Controller
{
    public function __construct(
        private readonly TelegramBotService $telegramService
    )
    {
    }


    /**
     * @throws Exception
     */
    public function setWebhook(Request $request)
    {

    }


    public function webhookHandler(TelegramBotApi $api)
    {
        $update = $api->getWebhookUpdate();

        try {
            $this->telegramService->handleUpdate($update);
        } catch (Exception $e) {
            Log::log(LogLevel::ERROR, $e->getMessage());
            Log::log(LogLevel::ERROR, $e->getTraceAsString());

        }
    }

    public function start()
    {
        return 'Это бот';
    }


}
