<?php

namespace Modules\Sales\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use BelongsToTenants;
    protected $fillable = ['name', 'price'];

    public function product()
    {
        return $this->belongsTo('Modules\Sales\Entities\Product');
    }

    public function orderDetail()
    {
        return $this->belongsToMany('Modules\Sales\Entities\OrderDetail');
    }
}
