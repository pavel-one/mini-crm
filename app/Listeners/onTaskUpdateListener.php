<?php

namespace App\Listeners;

use App\CrmClient;
use App\Events\onTaskUpdateEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class onTaskUpdateListener
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
     * @param onTaskUpdateEvent $event
     * @return void
     */
    public function handle(onTaskUpdateEvent $event)
    {
        /** @var CrmClient $client */
        $client = $event->client;
        /** @var User $user */
        $user = $event->user;
        $action = $event->action;
        $task = $event->task;
        $msg = '';

        switch ($action) {
            case 'start':
                if (!$task->time_tmp) {
                    $msg = 'Запустил задачу ' . $task->text;
                }
                break;
            case 'pause':
                $msg = 'Поставил на паузу задачу ' . $task->text;
                break;
            case 'refresh':
                $msg = 'Обнулил задачу ' . $task->text;
                break;
            case 'success':
                $msg = 'Выполнил задачу ' . $task->text;
                break;
            case 'remove':
                $msg = 'Удалил задачу ' . $task->text;
                break;
            case 'rename-task':
                $msg = 'Переименовал задачу ' . $task->text;
                break;
            default:
                $msg = 'Сделал неизвестное действие';
                break;
        }

        if ($msg) {
            $client->log()->create([
                'user_id' => $user->id,
                'name' => $msg
            ]);
        }

    }
}
