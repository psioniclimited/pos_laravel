<?php

namespace Modules\Sales\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use BelongsToTenants;
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany('Modules\Sales\Entities\Product');
    }
}
