<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    protected $fillable = ['message', 'files', 'read_user'];

    public function toUser()
    {
        return $this->hasOne('App\User', 'id', 'user_to');
    }

    public function fromUser()
    {
        return $this->hasOne('App\User', 'id', 'user_from');
    }

    public function read()
    {
        $this->read_user = true;
        $this->save();
    }

    public function unread()
    {
        $this->read_user = false;
        $this->save();
    }
}
