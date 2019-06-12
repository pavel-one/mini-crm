<?php

namespace App\Http\Controllers;

use App\ClientChat;
use Illuminate\Http\Request;

class ClentChatController extends Controller
{
    public function test(Request $req)
    {
//        $new = new ClientChat;
//        $new->save();
        return ClientChat::all();
    }
}
