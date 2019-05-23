<?php
/**
 * Created by PhpStorm.
 * User: shan
 * Date: 4/30/19
 * Time: 11:05 AM
 */

namespace App\Filters;


class AddressFilter extends Filters
{

    protected $filters = ['sort_by', 'global'];

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {
        return $this->builder
            ->where('city', 'like', '%' . $global . '%');
    }

}