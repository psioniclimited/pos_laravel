<?php

namespace Modules\Billing\Entities;

use App\Filters\CustomerFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use BelongsToTenants;

    protected $fillable = ['name', 'code', 'phone', 'email', 'nid', 'due_on', 'area_id', 'subscription_type_id', 'address', 'monthly_bill', 'status', 'bandwidth', 'shared', 'ppoe'];

    public function setDueOnAttribute($value)
    {
        $this->attributes['due_on'] = Carbon::parse($value)->addDay()->startOfMonth()->format('Y-m-d');
    }

    public function getCodeAttribute($value) {
        return sprintf('%04d', $value);
    }

    public function getSharedAttribute($value) {
        return $value === 1 ? 'Shared' : 'Dedicated';
    }

    /**
     * The customer's LocationDetails.
     */

    public function bill_collections()
    {
        return $this->hasMany('Modules\Billing\Entities\BillCollection');
    }

    public function fee_collections()
    {
        return $this->hasMany('Modules\Billing\Entities\FeeCollection');
    }

    public function subscription_type()
    {
        return $this->belongsTo('Modules\Billing\Entities\SubscriptionType');
    }

    public function area()
    {
        return $this->belongsTo('Modules\Billing\Entities\Area');
    }

    public function users()
    {
        return $this->belongsToMany('Modules\User\Entities\User');
    }

    public function complains()
    {
        return $this->hasMany('Modules\Billing\Entities\Complain');
    }

//    public function getTotalDueAttribute()
//    {
//        $totalDue = 0;
//        $currentMonth = (new Carbon('first day of this month'))->startOfDay();
//        $due_on = new Carbon($this->due_on);
//        if ($currentMonth->gte($due_on)) {
//            $numberOfDueMonths = $currentMonth->diffInMonths($due_on);
//            $totalDue = $this->monthly_bill * ++$numberOfDueMonths;
//        }
//        return $totalDue;
//    }

    public function scopeFilter($query, CustomerFilter $filters)
    {
        return $filters->apply($query);
    }
}
