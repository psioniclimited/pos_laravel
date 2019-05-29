<?php

namespace Modules\Sales\Entities;

use App\Filters\OrderFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use BelongsToTenants;

    public function order_details() {
        return $this->hasMany('Modules\Sales\Entities\OrderDetail');
    }

    public function client() {
        return $this->belongsTo('Modules\Sales\Entities\Client');
    }

    public function scopeFilter($query, OrderFilter $filters)
    {
        return $filters->apply($query);
    }
}
