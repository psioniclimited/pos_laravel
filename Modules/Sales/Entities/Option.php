<?php

namespace Modules\Sales\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use BelongsToTenants;
    protected $fillable = ['type', 'price'];

    public function product()
    {
        return $this->belongsTo('Modules\Sales\Entities\Product');
    }
}
