<?php

namespace Modules\Billing\Entities;

use App\Filters\FeeCollectionFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeCollection extends Model
{
    use SoftDeletes, BelongsToTenants;
    protected $fillable = ['customer_id', 'fee_type_id', 'total'];

    public function customer()
    {
        return $this->belongsTo('Modules\Billing\Entities\Customer');
    }

    public function fee_type()
    {
        return $this->belongsTo('Modules\Billing\Entities\FeeType');
    }

    public function scopeFilter($query, FeeCollectionFilter $filters)
    {
        return $filters->apply($query);
    }
}
