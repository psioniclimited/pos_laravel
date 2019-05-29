<?php

namespace Modules\Sales\Entities;

use App\Filters\ProductFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenants;
    protected $fillable = [
        'name', 'cost', 'sale_price', 'description', 'sku', 'can_sell', 'can_purchase', 'has_addons', 'has_options'
    ];

    public function category()
    {
        return $this->belongsTo('Modules\Sales\Entities\Category');
    }

    public function addons()
    {
        return $this->hasMany('Modules\Sales\Entities\Addon');
    }

    public function options()
    {
        return $this->hasMany('Modules\Sales\Entities\Option');
    }

    public function order_details() {
        return $this->hasMany('Modules\Sales\Entities\OrderDetail');
    }

    public function scopeFilter($query, ProductFilter $filters)
    {
        return $filters->apply($query);
    }
}
