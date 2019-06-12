<?php

namespace App\Http\Controllers;

use App\CrmClient;
use Illuminate\Http\Request;
use Image;

class CrmController extends Controller
{
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
        $messagess = $client->messagess()->get();

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
        if ($client->delete()) {
            return redirect()->route('crm');
        }
        return redirect()->route('CrmPage', $client->id);
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
