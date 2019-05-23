<?php

namespace Modules\User\Entities;

use App\Filters\UserFilter;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id', 'active', 'immutable'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder $query
     * @param  UserFilter $filters
     * @return Builder
     */
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

}
