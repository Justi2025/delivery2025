<?php
 

namespace App\Providers;

use App\Events\Zakaz\ZakazSozdanSobitie;
use App\Events\Zakaz\StatusZakazaIzmenenSobitie;
use App\Listeners\Sdelki\SlushatelIzmeneniaSdelkiSotrudnik;
use App\Listeners\Sdelki\SlushatelSozdaniaSdelki;
use App\Listeners\Sdelki\SlushatelIzmeneniaStatusSdelki;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ZakazSozdanSobitie::class => [
            SlushatelSozdaniaSdelki::class
        ],

        StatusZakazaIzmenenSobitie::class => [
            SlushatelIzmeneniaStatusSdelki::class,
            SlushatelIzmeneniaSdelkiSotrudnik::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
