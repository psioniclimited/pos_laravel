<?php

namespace Modules\Sales\Entities;

use App\Filters\OrderDetailFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use BelongsToTenants;
    protected $fillable = ['quantity','product_id','option_id','order_id','company_id','total'];

    public function order()
    {
        return $this->belongsTo('Modules\Sales\Entities\Order');
    }

    public function product()
    {
        return $this->belongsTo('Modules\Sales\Entities\Product');
    }

    public function option()
    {
        return $this->belongsTo('Modules\Sales\Entities\Option');
    }
}
