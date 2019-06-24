<?php

namespace App\Events;

use App\CrmClient;
use App\Task;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class onTaskUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $client;
    public $action;
    public $task;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, CrmClient $client, $action, Task $task)
    {
        $this->user = $user;
        $this->client = $client;
        $this->action = $action;
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
