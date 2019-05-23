<?php

namespace Modules\Sales\Entities;

use App\Filters\AddressFilter;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['address_type', 'street', 'flat', 'city','postal_code', 'country'];

    public function client(){
        return $this->belongsTo('Modules\Sales\Entities\client');
    }

    public function scopeFilter($query, AddressFilter $filters)
    {
        return $filters->apply($query);
    }
}
