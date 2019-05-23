<?php

namespace Modules\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class CustomerBillStatusDetail extends Model
{
    protected $fillable = ['status', 'effective_date', 'description', 'customer_id'];

    public function setEffectiveDateAttribute($value)
    {
        $this->attributes['effective_date'] = Carbon::parse($value)->addDay()->format('Y-m-d');
    }

    public function customer()
    {
        return $this->belongsToMany('Modules\Billing\Entities\Customer');
    }

    public function scopeFilter($query, CustomerBillStatusDetailFilter $filters)
    {
        return $filters->apply($query);
    }

}
