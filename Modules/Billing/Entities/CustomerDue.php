<?php

namespace Modules\Billing\Entities;

use App\Filters\CustomerDueListFilter;
use App\Filters\CustomerFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class CustomerDue extends Model
{
    protected $table = 'customers';
    use BelongsToTenants;

    public function setDueOnAttribute($value)
    {
        $this->attributes['due_on'] = Carbon::parse($value)->addDay()->startOfMonth()->format('Y-m-d');
    }

    public function users()
    {
        return $this->belongsToMany('Modules\User\Entities\User', 'customer_user', 'customer_id');
    }

    public function getCodeAttribute($value) {
        return sprintf('%04d', $value);
    }

    public function getSharedAttribute($value) {
        return $value === 1 ? 'Shared' : 'Dedicated';
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

    public function scopeFilter($query, CustomerDueListFilter $filters)
    {
        return $filters->apply($query);
    }
}
