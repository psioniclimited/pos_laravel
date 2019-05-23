<?php

namespace Modules\Billing\Entities;

use App\Filters\SubscriptionTypeFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    protected $fillable = ['name'];
    public function scopeFilter($query, SubscriptionTypeFilter $filters)
    {
        return $filters->apply($query);
    }

    public function customers()
    {
        return $this->hasMany('Modules\Billing\Entities\Customer');
    }
}
