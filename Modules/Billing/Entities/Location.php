<?php

namespace Modules\Billing\Entities;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name'];
    protected $with = ['location'];
    /**
     * Get the phone record associated with the user.
     */
    public function location()
    {
        return $this->hasOne('Modules\Billing\Entities\Location');
    }

    /**
     * Get the location that owns the phone.
     */
    public function parent_location()
    {
        return $this->belongsTo('Modules\Billing\Entities\Location');
    }

    /**
     * Get the location_details for the location.
     */
    public function location_details()
    {
        return $this->hasMany('Modules\Billing\Entities\LocationDetail');
    }
}
