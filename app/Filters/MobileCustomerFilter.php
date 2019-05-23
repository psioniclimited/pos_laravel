<?php

namespace App\Filters;

use Carbon\Carbon;

class MobileCustomerFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['sort_by', 'last_id', 'gt_updated_at'];

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }


//    protected function code($code){
//        return $this->builder->where('code',  $code);
//    }
    protected function last_id($last_id)
    {
        return $this->builder->orWhere('id', '>', $last_id);
    }


    protected function gt_updated_at($gt_updated_at)
    {
        return $this->builder->orWhere('updated_at', '>', $gt_updated_at);
    }
}