<?php

namespace App\Filters;

use Carbon\Carbon;

class OrderFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['name', 'date', 'sort_by', 'global'];

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

    protected function date($date)
    {
        $dateArray = explode(',', $date);
        $dateArray[0] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[0])->format('Y-m-d');
        if (sizeof($dateArray) > 1 && !empty($dateArray[1])) {
            $dateArray[1] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[1])->format('Y-m-d');
            return $this->builder
                ->whereDate('date', '>=', $dateArray[0])
                ->whereDate('date', '<=', $dateArray[1]);
        } else {
            return $this->builder
                ->whereDate('date', '=', $dateArray[0]);
        }
    }

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {
        return $this->builder
            ->where('clients.name', 'like', '%' . $global . '%');
    }
}