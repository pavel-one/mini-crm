<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'name', 'user_id', 'client_id'
    ];

    public function client()
    {
        return $this->hasOne('App\CrmClient', 'id', 'client_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }


}
