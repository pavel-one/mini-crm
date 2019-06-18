<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Массово присваиваемые атрибуты.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'time',
        'time_tmp',
        'user_tmp',
        'user_active',
        'client_id',
        'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activeUser()
    {
        return $this->hasOne('App\User', 'id', 'user_active');
    }

    public function userTmp()
    {
        return $this->hasOne('App\User', 'id', 'user_tmp');
    }

    public function client()
    {
        return $this->hasOne('App\CrmClient', 'id', 'client_id');
    }
}
