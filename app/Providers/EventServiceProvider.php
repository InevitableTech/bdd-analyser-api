<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\ProjectCreatedEvent::class => [
            \App\Listeners\ProjectCreatedListener::class,
        ],
        \App\Events\UserCreatedEvent::class => [
            \App\Listeners\UserCreatedListener::class,
        ]
    ];
}
