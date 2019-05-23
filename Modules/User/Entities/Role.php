<?php

namespace Modules\User\Entities;

use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;
use App\Filters\RoleFilter;

class Role extends EntrustRole
{
    use BelongsToTenants;

    protected $fillable = ['name', 'display_name', 'description'];

    public function scopeFilter($query, RoleFilter $filters)
    {
        return $filters->apply($query);
    }

    public function user()
    {
        return $this->belongsToMany('Modules\User\Entities\User');
    }
}
