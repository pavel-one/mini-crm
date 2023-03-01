<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TaskFile extends Model
{
    protected $table = 'task_files';
    protected $fillable = ['name', 'patch', 'size', 'properties'];

    public function client()
    {
        return $this->belongsTo('App\CrmClient', 'id', 'client_id');
    }
}
