<?php

namespace Modules\Sales\Entities;

use App\Filters\ClientFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Client extends Model
{
    use BelongsToTenants;
    protected $fillable = ['name', 'tin_number', 'website', 'phone', 'currency', 'payment_term', 'public_note',
        'private_note', 'company_size', 'industry'];

    public function addresses(){
        return $this->hasMany('Modules\Sales\Entities\Address');
    }

    public function contacts(){
        return $this->hasMany('Modules\Sales\Entities\Contact');
    }

    public function orders(){
        return $this->hasMany('Modules\Sales\Entities\Order');
    }

    public function scopeFilter($query, ClientFilter $filters)
    {
        return $filters->apply($query);
    }
}
