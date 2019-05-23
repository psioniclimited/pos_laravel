<?php

namespace Modules\Billing\Entities;

use App\Filters\ComplainFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use BelongsToTenants;
    protected $fillable = ['date', 'complain_status_id', 'description', 'customer_id'];
    public function scopeFilter($query, ComplainFilter $filters)
    {
        return $filters->apply($query);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function complain_status()
    {
        return $this->belongsTo('Modules\Billing\Entities\ComplainStatus');
    }

    public function customer()
    {
        return $this->belongsTo('Modules\Billing\Entities\Customer');
    }
}
