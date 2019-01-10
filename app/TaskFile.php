<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class taskFile extends Model
{
    protected $table = 'task_files';
    protected $fillable = ['name', 'patch', 'size', 'properties'];

    public function client()
    {
        return $this->belongsTo('App\CrmClient', 'id', 'client_id');
    }
//    public function update(array $attributes = [], array $options = [])
//    {
//        if ($name = $attributes['name']) {
//            $patch = storage_path('app/public').'/'.$this->patch;
//            $pn = pathinfo($patch);
//            Storage::move($patch, $pn['dirname'].'/'.$name.'.'.$pn['extension']);
//        }
//        return parent::update($attributes, $options);
//    }
}
