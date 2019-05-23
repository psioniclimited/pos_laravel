<?php

namespace App\Filters;

class CustomerBillDetailFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort_by'];

//    protected function customer_id($id)
//    {
//        return $this->builder->where('customer_id', $id);
//    }

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
//        $sort_by = explode('.', $sort_by);
//        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
//        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {

    }
}