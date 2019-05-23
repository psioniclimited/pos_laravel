<?php

namespace Modules\Billing\Entities;

use App\Filters\CustomerStatusFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class CustomerStatus extends Model
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

    public function scopeFilter($query, CustomerStatusFilter $filters)
    {
        return $filters->apply($query);
    }
}
