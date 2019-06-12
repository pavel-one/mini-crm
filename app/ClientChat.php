<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class ClientChat extends Model
{
    public $fillable = ['message'];
    public $table = 'client_chat';

    public function client()
    {
        return $this->belongsTo('App\CrmClient', 'id', 'client_id');
    }

    public function user()
    {
        return $this->belongsTo('App\CrmClient', 'id', 'client_id');
    }

    public function save(array $options = [])
    {
        $this->user_id = Auth::user()->id;
//        $this->client_id = 1;
//        $this->message = 'test';
        return parent::save($options);
    }
}
