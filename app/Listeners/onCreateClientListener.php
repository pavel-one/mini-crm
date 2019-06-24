<?php

namespace App\Listeners;

use App\CrmClient;
use App\Events\onCreateClientEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class onCreateClientListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //..
    }

    /**
     * Handle the event.
     *
     * @param onCreateClientEvent $event
     * @return void
     */
    public function handle(onCreateClientEvent $event)
    {
        $users = User::all();
        /** @var CrmClient $client */
        $client = $event->client;
        $user = $event->user;

        /** @var User $user_item */
        foreach ($users as $user_item) {
            $link = route('CrmPage', $client->id);
            $client_name = $client->name;
            $user_item->sendTelegram("У нас новый клиент «*{$client_name}*»! Просим всех ознакомиться! \n {$link}");
        }
        $user->log()->create([
            'client_id' => $client->id,
            'name' => 'Создал этого клиента',
            'action' => 'Client_create'
        ]);
    }
}
