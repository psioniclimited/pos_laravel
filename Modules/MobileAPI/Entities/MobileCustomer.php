<?php

namespace Modules\MobileAPI\Entities;

use App\Filters\MobileCustomerFilter;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;


class MobileCustomer extends Model
{
    protected $table = 'customers';
    use BelongsToTenants;

    public function scopeFilter($query, MobileCustomerFilter $filters)
    {
        return $filters->apply($query);
    }
}
