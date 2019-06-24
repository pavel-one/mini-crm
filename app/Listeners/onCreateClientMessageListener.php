<?php

namespace App\Listeners;

use App\Events\onCreateClientMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class onCreateClientMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  onCreateClientMessageEvent  $event
     * @return void
     */
    public function handle(onCreateClientMessageEvent $event)
    {
        //
    }
}
