<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmClient extends Model
{
    public $fillable = [
        'name',
        'active',
        'dead',
        'start',
        'chargeable_user',
        'url',
        'phone',
        'photo',
        'client_name',
        'files',
        'email',
        'description',
        'full_description'
    ];

    public function delete()
    {
        $this->access()->delete();
        $this->tasks()->delete();
        $this->payments()->delete();
        $this->files()->delete();
        $this->messagess()->delete();
        $this->log()->delete();
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

    public function messagess()
    {
        return $this->hasMany('App\ClientChat', 'client_id', 'id');
    }

    public function getChargeable()
    {
        return $this->hasOne('App\User', 'id', 'chargeable_user');
    }

    public function log()
    {
        return $this->hasMany('App\UserLog', 'client_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * Показывает % завершения задачи
     * @return array
     */
    public function getPercent()
    {
        $all = $this->tasks()->count();
        $success = $this->tasks()->where('active', 1)->count();
        $percent = round(($success * 100) / $all);
        return ['all' => $all, 'success' => $success, 'percent' => $percent];
    }
}
