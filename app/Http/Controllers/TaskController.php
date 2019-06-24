<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\Events\onTaskUpdateEvent;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request, CrmClient $client)
    {
        if (!$text = $request->text) {
            return ['success' => false, 'msg' => 'Нет задачи'];
        }
        $text = linksHandler($text);

        $request->user()->tasks()->create([
            'text' => $text,
            'client_id' => $client->id
        ]);

        return ['success' => true, 'msg' => 'Задача успешно создана'];
    }

    public function update(Request $request, CrmClient $client, Task $task)
    {
        $action = $request->action;
        $time_tmp = $task->time_tmp;
        $time = $task->time;
        /** @var User $currentUser */
        $currentUser = Auth::user();

        Event::fire(new onTaskUpdateEvent($currentUser, $client, $action, $task));
        switch ($action) {
            case 'start':
                $task->update([
                    'time_tmp' => $time_tmp + 1,
                    'user_tmp' => $currentUser->id,
                ]);

                return response()->json([
                    'time' => timeFormat($task->time_tmp),
                    'second' => $task->time_tmp
                ]);
                break;
            case 'pause':
                if (!$time_tmp) {
                    return $this->error('Таймер не был запущен');
                }
                $task->update([
                    'time' => $time + $time_tmp,
                    'time_tmp' => 0,
                    'user_tmp' => null,
                ]);
                return $this->success('Таймер остановлен, время обновлено');
                break;
            case 'refresh':
                $task->update([
                    'time' => 0,
                    'time_tmp' => 0,
                    'active' => 0,
                ]);
                return $this->success('Задача сброшена');
                break;
            case 'success':
                $task->update([
                    'active' => 1,
                ]);
                return $this->success('Задача выполнена');
                break;
            case 'remove':
                $task->delete();
                return $this->error('Задача удалена');
                break;
            case 'rename-task':
                $text = linksHandler($request->text);
                $task->update([
                    'text' => $text,
                ]);
                return $this->success('Задача изменена');
                break;
        }
        return $this->error('Ошибка');
    }

    public function error($msg)
    {
        return ['success' => false, 'msg' => $msg];
    }

    public function success($msg)
    {
        return ['success' => true, 'msg' => $msg];
    }
}
