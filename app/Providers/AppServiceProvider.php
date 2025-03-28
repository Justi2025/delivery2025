<?php
 

namespace App\Providers;

use App\Http\Controllers\GlavniPoTelegramBotu;
use App\Http\Services\Notification\NotificationSender;
use App\Http\Services\Notification\TelegramNotificationSender;
use App\Khranilischa\Authentication\Token\TokenRepository;
use App\Khranilischa\Authentication\Token\TokenRepositoryInterface;
use App\Khranilischa\Klienti\CustomersRepository;
use App\Khranilischa\Klienti\CustomersRepositoryInterface;
use App\Khranilischa\Sdelki\InyterfaceRepositoriaSdelok;
use App\Khranilischa\Sdelki\SdelkiRepository;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Telegram\TelegramService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->when(ServisAutentificatcii::class)
            ->needs('$jwtSecret')
            ->give(env('JWT_SECRET'));

        $this->app->when(ServisAutentificatcii::class)
            ->needs('$refreshTTL')
            ->give(env('JWT_REFRESH_TOKEN_TTL'));

        $this->app->singleton(InyterfaceRepositoriaSdelok::class, SdelkiRepository::class);
        $this->app->singleton(TokenRepositoryInterface::class, TokenRepository::class);
        $this->app->singleton(CustomersRepositoryInterface::class, CustomersRepository::class);

        $this->app->singleton(NotificationSender::class, TelegramNotificationSender::class);


        $this->app->when(GlavniPoTelegramBotu::class)
            ->needs('$botApiToken')
            ->give(env('TELEGRAM_BOT_API_TOKEN'));


        $this->app->when(TelegramService::class)
            ->needs('$botApiToken')
            ->give(env('TELEGRAM_BOT_API_TOKEN'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
