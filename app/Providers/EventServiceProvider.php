<?php

namespace App\Providers;

use App\Events\MatchProcessed;
use App\Events\UserClientRegistered;
use App\Events\UserContractorRegistered;
use App\Listeners\CreateStripeClient;
use App\Listeners\SendEmailMacthes;
use App\Listeners\SendEmailMatches;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        UserContractorRegistered::class => [
            CreateStripeClient::class
        ],
        MatchProcessed::class => [
            SendEmailMatches::class
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
