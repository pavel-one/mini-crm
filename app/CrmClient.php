<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmClient extends Model
{
    public $fillable = ['name', 'url', 'phone', 'email', 'description', 'full_description'];

    public function delete()
    {
        $this->access()->delete();
        $this->tasks()->delete();
        $this->payments()->delete();
        $this->files()->delete();
        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function access()
    {
        return $this->hasMany('App\CrmClientAccess', 'client_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'client_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\TaskPayment', 'client_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\TaskFile', 'client_id', 'id');
    }
}
