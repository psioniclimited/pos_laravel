<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['first_name','last_name','email','phone'];

    public function client(){
        return $this->belongsTo('Modules\Sales\Entities\Client');
    }
}
