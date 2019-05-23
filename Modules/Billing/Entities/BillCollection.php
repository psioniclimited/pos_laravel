<?php

namespace Modules\Billing\Entities;

use App\Filters\BillCollectionFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillCollection extends Model
{
    use SoftDeletes, BelongsToTenants;
    protected $fillable = ['user_id', 'customer_id', 'no_of_months', 'total', 'discount', 'due_on', 'latest', 'lat', 'lon'];
    protected $appends = ['latest_refund'];

//    protected $appends = ['grand_total'];

    public function customer()
    {
        return $this->belongsTo('Modules\Billing\Entities\Customer');
    }

    public function getTotalAttribute($value)
    {
        return $value;
    }

//    public function getGrandTotalAttribute()
//    {
//        return $this->total - $this->discount;
//    }

    public function getNoOfMonthsAttribute($value)
    {
        $old_due_on = new Carbon($this->due_on);
        $text = '';
        while ($value > 0) {
            $text = $text . $old_due_on->format('M y') . '  ';
            $old_due_on->addMonth();
            $value--;
        }
        return $text;
    }

    public function getLatestRefundAttribute()
    {
        $latest = BillCollection::where('customer_id', $this->customer_id)->orderBy('due_on', 'desc')->first();
        if ($latest != null) {
            if ($latest->id == $this->id)
                return true;
            else
                return false;
        }
    }

    public function getCodeAttribute($value) {
        return sprintf('%04d', $value);
    }

    public function getSharedAttribute($value) {
        return $value === 1 ? 'Shared' : 'Dedicated';
    }

    public function scopeFilter($query, BillCollectionFilter $filters)
    {
        return $filters->apply($query);
    }
}
