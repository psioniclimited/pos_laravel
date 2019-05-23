<?php

namespace App\Filters;

use Carbon\Carbon;

class ProductFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['global', 'sort_by', 'category_id'];

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {
        return $this->builder->where(function ($query) use ($global) {
        });
    }

    protected function category_id($category_id) {
        return $this->builder->where('category_id', $category_id);
    }
}