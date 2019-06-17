<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Image;
use NikitaKiselev\SendPulse\SendPulse;

class CrmController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $list = CrmClient::orderBy('active', 'desc')->get();
        return view('crm.list', ['pagetitle' => 'Список клиентов', 'clients' => $list]);
    }

    public function ClientPage(CrmClient $client)
    {
        $access = $client->access()->get();
        $payments = $client->payments()->get();
        $messagess = $client->messagess()->get()->sortByDesc('created_at');
        $users = User::select('id', 'name')->get();

        $tasks = $client->tasks()->orderBy('active', 'asc')->orderBy('time', 'desc')->get();
        $allTime = 0;
        if (count($tasks)) {
            foreach ($tasks as $task) {
                $allTime += $task->time;
            }
        }

        $files = $client->files()->get();
        $fileSizes = 0;
        if (count($files)) {
            foreach ($files as $file) {
                $fileSizes += $file->size;
            }
        }

        $clientActive = $client->payments()->where('active', 1)->get();
        $clientPrice = 0;
        if (count($clientActive)) {
            foreach ($clientActive as $item) {
                $clientPrice += $item->price;
            }
        }

        $params = [
            'pagetitle' => $client->name,
            'client' => $client,
            'access' => false,
            'tasks' => false,
            'payments' => false,
            'files' => false,
            'messagess' => false,
            'fileSizes' => $fileSizes,
            'clientPrice' => $clientPrice,
            'allTime' => $allTime,
            'allUsers' => $users,
            'user' => Auth::user()
        ];

        if (count($access)) {
            $params['access'] = $access;
        }
        if (count($tasks)) {
            $params['tasks'] = $tasks;
        }
        if (count($payments)) {
            $params['payments'] = $payments;
        }
        if (count($files)) {
            $params['files'] = $files;
        }
        if (count($messagess)) {
            $params['messagess'] = $messagess;
        }

//        dd($params);

        return view('crm.page', $params);
    }

    public function store(Request $request)
    {
        $client = CrmClient::create($request->all());
        $dataPush = [
            'title' => 'У нас новый проект!',
            'website_id' => 44319,
            'body' => $client->name,
            'link' => route('CrmPage', $client->id),
            'ttl' => 10,
        ];
        SendPulse::createPushTask($dataPush);
        return redirect()->route('CrmPage', $client->id);
    }

    public function access(CrmClient $client, Request $request)
    {
        if ($request->all) {
            return $this->_accessCreates($request->all, $client);
        }

        $access = $client->access()->find($request->id);
        if (!$access) {
            return ['success' => false, 'msg' => 'Не найдено'];
        }

        $val = $request->value;
        $id = $request->id;
        $name = $request->name;

        if (!$val && $id !== 0) {
            $access->delete();
            return ['success' => true, 'msg' => 'Успешно удалено'];
        }

        $access->update([
            'name' => $name,
            'value' => $val
        ]);


        return ['success' => true, 'msg' => 'Успешно'];
    }

    public function _accessCreates(array $data, CrmClient $client)
    {
        foreach ($data as $item) {
            $client->access()->create([
                'name' => $item['name'],
                'value' => $item['value']
            ]);
        }
        return ['success' => true, 'msg' => 'Доступы успешно созданы'];
    }

    public function update(CrmClient $client, Request $request)
    {
        $client->update($request->toArray());

        return [
            'success' => true,
            'msg' => 'Успешно сохранено',
            'obj' => $client->toArray()
        ];
    }

    public function remove(CrmClient $client)
    {
        $user = Auth::user();
        if (!$user->sudo) {
            return redirect()->route('CrmPage', $client->id);
        }
        if ($client->delete()) {
            return redirect()->route('crm');
        }
        return redirect()->route('CrmPage', $client->id);
    }

    public function actions(CrmClient $client, Request $request)
    {
        $user = Auth::user();
        $actions = $request->action;
        if (!$actions) {
            return $this->error('Не задано действие');
        }
        if (!$user->sudo) {
            return $this->error('Вы не имеете прав для этого действия');
        }

        switch ($actions) {
            case 'switchactive':
                $newActive = $client->active;
                if ($newActive == 1) {
                    $newActive = false;
                    $client->update([
                        'active' => $newActive
                    ]);
                } else {
                    $newActive = true;
                    $client->update([
                        'active' => $newActive,
                        'start' => date('Y-m-d'),
                    ]);
                }

                return $this->success('Успешно!');
                break;
            case 'deadline':
                if (!$date = $request->date) {
                    return $this->error('Не задана дата дедлайна');
                }
                $client->update([
                    'dead' => $request->date,
                ]);
                return $this->success('Дата дедлайна успешно установлена!');
                break;
            case 'user_chargeable':
                if (!$user_id = $request->user_id) {
                    return $this->error('Не задан пользователь');
                }
                $client->update([
                    'chargeable_user' => $user_id,
                ]);
                $user = $client->getChargeable()->first();
                Mail::send('emails.chargeable',
                    ['client' => $client->toArray(), 'user' => $user->toArray()],
                    function ($m) use ($user) {
                        $m->to($user->email, 'CRM SK')->subject('Ты ответственный!');
                    });
                return $this->success('Ответственный успешно задан');
                break;
        }
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
