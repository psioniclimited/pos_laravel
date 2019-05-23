<?php

namespace App\Filters;

class LocationDetailFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['id', 'location_id', 'name', 'location_detail_id', 'sort_by', 'global'];

    protected function id($id)
    {
        return $this->builder->where('id', $id);
    }

    protected function location_id($location_id)
    {
        return $this->builder->where('location_id', $location_id);
    }

    protected function name($name)
    {
        return $this->builder->where('name', 'like', '%' . $name . '%');
    }

    protected function location_detail_id($location_detail_id)
    {
        return $this->builder->where('location_detail_id', $location_detail_id);
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
    }
}