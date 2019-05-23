<?php

namespace Modules\User\Entities;

use App\Filters\PermissionFilter;
use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['name', 'display_name', 'description'];

    public function scopeFilter($query, PermissionFilter $filters)
    {
        return $filters->apply($query);
    }
}
