<?php

namespace App\Filters;

use Modules\User\Entities\User;

class UserFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['name', 'sort_by', 'global'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return
     */
    protected function name($username)
    {
        return $this->builder->where('name', 'like', '%' . $username . '%');
    }

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function company_id($company_id)
    {
        return $this->builder->where('users.company_id', 'like', '%' . $company_id . '%');
    }

    protected function global($global)
    {
        return $this->builder
            ->where('users.name', 'like', '%' . $global . '%')
            ->orWhere('users.email', 'like', '%' . $global . '%')
            ->orWhereHas('roles', function ($query) use ($global) {
                $query->where('name', 'like', '%' . $global . '%');
            });
    }

}