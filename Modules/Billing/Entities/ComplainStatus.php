<?php

namespace Modules\Billing\Entities;

use App\Filters\ComplainStatusFilter;
use Illuminate\Database\Eloquent\Model;

class ComplainStatus extends Model
{
    protected $fillable = ['name'];
    public function scopeFilter($query, ComplainStatusFilter $filters)
    {
        return $filters->apply($query);
    }

    public function complains()
    {
        return $this->hasMany('Modules\Billing\Entities\Complain');
    }


}
