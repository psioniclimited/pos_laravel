<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'valid_to', 'pricing_plan_id'];

    public function pricing_plan()
    {
        return $this->belongsTo('Modules\User\Entities\PricingPlan');
    }
}
