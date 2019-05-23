<?php

namespace Modules\User\Entities;

use App\Filters\UserFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    protected $table = 'users';
    use BelongsToTenants;
    protected $fillable = ['name', 'email', 'password', 'company_id', 'active'];
    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function scopeFilter($query, UserFilter $filters)
    {
        return $filters->apply($query);
    }

    public function roles()
    {
        return $this->belongsToMany('Modules\User\Entities\Role', 'role_user', 'user_id');
    }

//    public function roles()
//    {
//        return $this->belongsToMany('Modules\User\Entities\Role');
//    }

    public function customers()
    {
        return $this->belongsToMany('Modules\Billing\Entities\Customer');
    }

    public function company()
    {
        return $this->belongsTo('Modules\User\Entities\Company');
    }
}
