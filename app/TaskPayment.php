<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskPayment extends Model
{
    protected $table = 'task_payments';
    protected $fillable = ['name', 'price', 'pay_date', 'active'];

    public function client()
    {
        return $this->belongsTo('App\CrmClient');
    }
}
