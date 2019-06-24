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
        $actionBD = 'Task_'.$action;

        switch ($action) {
            case 'start':
                if (!$task->time_tmp) {
                    $msg = "Запустил задачу <b>«{$task->text}»</b>";
                }
                break;
            case 'pause':
                $msg = "Поставил на паузу задачу <b>«{$task->text}»</b>";
                break;
            case 'refresh':
                $msg = "Обнулил задачу <b>«{$task->text}»</b>";
                break;
            case 'success':
                $msg = "Выполнил задачу <b>«{$task->text}»</b>";
                break;
            case 'remove':
                $msg = "Удалил задачу <b>«{$task->text}»</b>";
                break;
            case 'rename-task':
                $msg = "Переименовал задачу <b>«{$task->text}»</b>";
                break;
            case 'create':
                $msg = "Создал задачу <b>«{$task->text}»</b>";
                break;
            default:
                $msg = 'Сделал неизвестное действие';
                break;
        }

        if ($msg) {
            $client->log()->create([
                'user_id' => $user->id,
                'name' => $msg,
                'action' => $actionBD
            ]);
        }

    }
}
