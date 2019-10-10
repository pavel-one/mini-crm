<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = Server::orderBy('name', 'ASC')->get();
//        dd($servers);
        return view('crm.servers', ['servers' => $servers, 'pagetitle' => 'Список серверов', 'route' => 'Servers']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $requestArr = $request->all();
        $values = $requestArr['values'];
        $requestArr['hosting'] = '';
        $tmpArr = [];

        if ($names = $requestArr['names']) {
            foreach ($names as $key => $name) {
                if (!$name) continue;
                $val = $values[$key] ?: '';
                if (!$val) continue;
                $tmpArr[$name] = $val;
            }
            unset($requestArr['names']);
            unset($requestArr['values']);
            $requestArr['hosting'] = json_encode($tmpArr);
        }
        $server = Server::create($requestArr);
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function edit(Server $server)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server)
    {
        //
    }
}
