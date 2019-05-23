<?php

namespace Modules\Billing\Entities;

use App\Filters\CustomerBillDetailFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class CustomerBillDetail extends Model
{
    use BelongsToTenants;

    protected $fillable = ['monthly_charge', 'effective_date', 'description', 'customer_id', 'active'];

    public function setEffectiveDateAttribute($value)
    {
        $this->attributes['effective_date'] = Carbon::parse($value)->addDay()->startOfMonth()->format('Y-m-d');
    }

    public function customer()
    {
        return $this->belongsToMany('Modules\Billing\Entities\Customer');
    }

    public function scopeFilter($query, CustomerBillDetailFilter $filters)
    {
        return $filters->apply($query);
    }

}
