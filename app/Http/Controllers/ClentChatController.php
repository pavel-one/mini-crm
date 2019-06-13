<?php

namespace App\Http\Controllers;

use App\ClientChat;
use App\CrmClient;
use Illuminate\Http\Request;

class ClentChatController extends Controller
{
    public function create(Request $request, CrmClient $client)
    {
//        dd($request->all());
        return $client->messagess()->create($request->all());
    }

    public function all(Request $request, CrmClient $client)
    {
        return $client->messagess()->get()->sortByDesc('created_at');
    }
}
