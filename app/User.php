<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'photo', 'phone', 'nick'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function messagess()
    {
        return $this->hasMany('App\ClientChat', 'user_id', 'id');
    }

    public function delete()
    {
        $this->tasks()->delete();
        $this->messagess()->delete();

        return parent::delete();
    }

    public function save(array $options = [])
    {
        if ($this->nick) {
            $this->nick = str_replace(' ', '_', $this->nick);
        }
        return parent::save($options);
    }
}
