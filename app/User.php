<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

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

    public function messagessClients()
    {
        return $this->hasMany('App\ClientChat', 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\UserMessage', 'user_from', 'id');
    }

    public function myMessages()
    {
        return $this->hasMany('App\UserMessage', 'user_to', 'id');
    }

    public function messageTopics()
    {
        return $this->myMessages()->groupBy('user_from');
    }

    public function delete()
    {
        $this->tasks()->delete();
        $this->messagessClients()->delete();
        $this->messages()->delete();
        $this->myMessages()->delete();

        return parent::delete();
    }

    public function sendMessage($msg, Request $request)
    {
        $message = new UserMessage;
        $message->user_to = $this->id;
        $message->user_from = Auth::user()->id;
        $message->text = $msg;
        $message->save();
    }

    public function unreadMessages($user_id = null)
    {
        if (!$user_id) {
            $col = UserMessage::where('user_to', $this->id)->where('read_user', false);
        } else {
            $col = UserMessage::where('user_to', $this->id)->where('read_user', false)->where('user_from', $user_id);
        }

        return $col;
    }

    public function save(array $options = [])
    {
        if ($this->nick) {
            $this->nick = str_replace(' ', '_', $this->nick);
        }
        return parent::save($options);
    }
}
