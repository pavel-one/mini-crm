<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request, CrmClient $client)
    {
        if (!$request->text) {
            return ['success' => false, 'msg' => 'Нет задачи'];
        }

        $request->user()->tasks()->create([
            'text' => $request->text,
            'client_id' => $client->id
        ]);

        return ['success' => true, 'msg' => 'Задача успешно создана'];
    }

    public function update(Request $request, CrmClient $client, Task $task)
    {
        $action = $request->action;
        $time_tmp = $task->time_tmp;
        $time = $task->time;

        switch ($action) {
            case 'start':
                $task->update([
                    'time_tmp' => $time_tmp + 1,
                ]);
                return timeFormat($task->time_tmp);
                break;
            case 'pause':
                if (!$time_tmp) {
                    return $this->error('Таймер не был запущен');
                }
                $task->update([
                    'time' => $time + $time_tmp,
                    'time_tmp' => 0
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
