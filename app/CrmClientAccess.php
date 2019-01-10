<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmClientAccess extends Model
{
    protected $table = 'crm_client_access';
    public $fillable = ['client_id', 'name', 'value'];

    public function client()
    {
        return $this->hasOne('App\CrmClient', 'id', 'client_id');
    }
}
