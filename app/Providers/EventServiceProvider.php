<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\FetchPollingSentimentEvent;
use App\Events\CreatePollingSentimentEvent;
use App\Events\FetchKegiatanPoskoSentimentEvent;
use App\Listeners\FetchPollingSentimentListener;
use App\Events\CreateKegiatanPoskoSentimentEvent;
use App\Listeners\CreatePollingSentimentListener;
use App\Listeners\FetchKegiatanPoskoSentimentListener;
use App\Listeners\CreateKegiatanPoskoSentimentListener;
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
        CreateKegiatanPoskoSentimentEvent::class => [
            CreateKegiatanPoskoSentimentListener::class
        ],
        FetchKegiatanPoskoSentimentEvent::class => [
            FetchKegiatanPoskoSentimentListener::class
        ],
        CreatePollingSentimentEvent::class => [
            CreatePollingSentimentListener::class
        ],
        FetchPollingSentimentEvent::class => [
            FetchPollingSentimentListener::class
        ],
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
