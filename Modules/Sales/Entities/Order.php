<?php

namespace Modules\Sales\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use BelongsToTenants;
    protected $fillable = [];

    public function order_details()
    {
        return $this->hasMany('Modules\Sales\Entities\OrderDetail');
    }
}
