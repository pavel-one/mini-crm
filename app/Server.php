<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    public $fillable = [
        'name',
        'description',
        'host',
        'login',
        'password',
        'hosting',
    ];
}
