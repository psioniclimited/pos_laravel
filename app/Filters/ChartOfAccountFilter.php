<?php

namespace App\Filters;

class ChartOfAccountFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['parent_account_id', 'is_payment_account', 'sort_by', 'global', 'parent_accounts'];

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return
     */
    protected function parent_account_id($parent_account_id)
    {
        return $this->builder
            ->where('parent_account_id', $parent_account_id);
    }

    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return
     */
    protected function is_payment_account($is_payment_account)
    {
        return $this->builder
            ->where('is_payment_account', $is_payment_account);
    }

    protected  function parent_accounts($parent) {
        return $this->builder
            ->whereNull('parent_account_id');
    }

    protected function sort_by($sort_by)
    {
//        $sort_by = explode('.', $sort_by);
//        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
//        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {
        return $this->builder
            ->where('name', 'like', '%' . $global . '%');
//            ->orWhere('display_name', 'like', '%' . $global . '%')
//            ->orWhere('description', 'like', '%' . $global . '%');
    }
}