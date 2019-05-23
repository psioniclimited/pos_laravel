<?php

namespace Modules\Billing\Entities;

use App\Filters\AreaFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use BelongsToTenants;
    protected $fillable = ['name', 'description'];
    public function scopeFilter($query, AreaFilter $filters)
    {
        return $filters->apply($query);
    }

    public function customers()
    {
        return $this->hasMany('Modules\Billing\Entities\Customer');
    }
}
