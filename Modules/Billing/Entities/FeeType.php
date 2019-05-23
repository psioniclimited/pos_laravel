<?php

namespace Modules\Billing\Entities;

use App\Filters\FeeTypeFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    use BelongsToTenants;

    protected $fillable = ['name', 'amount', 'description'];

    public function fee_collections()
    {
        return $this->hasMany('Modules\Billing\Entities\FeeCollection');
    }

    public function scopeFilter($query, FeeTypeFilter $filters)
    {
        return $filters->apply($query);
    }
}
