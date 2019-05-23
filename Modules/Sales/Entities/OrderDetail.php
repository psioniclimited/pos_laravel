<?php

namespace Modules\Sales\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use BelongsToTenants;
    protected $fillable = [];

    public function order()
    {
        return $this->belongsTo('Modules\Sales\Entities\Order');
    }
}
