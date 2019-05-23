<?php

namespace Modules\Billing\Entities;

use App\Filters\LocationDetailFilter;
use Illuminate\Database\Eloquent\Model;

class LocationDetail extends Model
{
    protected $fillable = ['name', 'location_id', 'location_detail_id'];

    /**
     * Get the Location that owns the LocationDetail.
     */
    public function location()
    {
        return $this->belongsTo('Modules\Billing\Entities\Location');
    }

    /**
     * Get the Location that owns the LocationDetail.
     */
    public function location_no_hierarchy()
    {
        return $this->belongsTo('Modules\Billing\Entities\LocationNoHierarchy', 'location_id');
    }

    public function scopeFilter($query, LocationDetailFilter $filters)
    {
        return $filters->apply($query);
    }
}
