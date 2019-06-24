<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\onTaskUpdateEvent' => [
            'App\Listeners\onTaskUpdateListener'
        ],
        'App\Events\onCreateClientEvent' => [
            'App\Listeners\onCreateClientListener'
        ],
        'App\Events\onCreateClientMessageEvent' => [
            'App\Listeners\onCreateClientMessageListener'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
