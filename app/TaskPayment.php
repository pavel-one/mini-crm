<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskPayment extends Model
{
    protected $table = 'task_payments';
    protected $fillable = ['name', 'price', 'active'];

    public function client()
    {
        return $this->belongsTo('App\CrmClient', 'id', 'client_id');
    }
}
