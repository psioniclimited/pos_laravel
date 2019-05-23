<?php

namespace App\Filters;

class ComplainFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['name', 'status', 'sort_by', 'global'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return
     */
    protected function name($username)
    {
//        $user = User::where('name', $username)->firstOrFail();
//        return $this->builder->where('id', $user->id);
    }

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function status($status)
    {
        return $this->builder->where('complain_statuses.name', 'like', '%' . $status . '%');
    }

    protected function global($global)
    {
        return $this->builder
            ->where('description', 'like', '%' . $global . '%')
            ->orWhere('code', 'like', '%' . $global . '%')
            ->orWhere('customers.name', 'like', '%' . $global . '%')
            ->orWhere('phone', 'like', '%' . $global . '%')
            ->orWhere('complain_statuses.name', 'like', '%' . $global . '%');
    }
}