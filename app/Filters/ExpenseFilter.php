<?php

namespace App\Filters;

use Carbon\Carbon;

class ExpenseFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['expense_type', 'date', 'paid_with', 'sort_by', 'global'];

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

    protected function expense_type($expense_type)
    {
        return $this->builder->whereHas('expense_details.chart_of_account', function ($query) use ($expense_type) {
            $query->where('id', $expense_type);
        });
    }

    protected function date($date)
    {
        $dateArray = explode(',', $date);
        $dateArray[0] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[0])->format('Y-m-d');
        if (sizeof($dateArray) > 1 && !empty($dateArray[1])) {
            $dateArray[1] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[1])->format('Y-m-d');
            return $this->builder
                ->whereDate('expenses.date', '>=', $dateArray[0])
                ->whereDate('expenses.date', '<=', $dateArray[1]);
        }
        else {
            return $this->builder
                ->whereDate('expenses.date', '=', $dateArray[0]);
        }
    }

    protected function global($global)
    {
        return $this->builder->where(function ($query) use ($global) {
            $query->where('expenses.description', 'like', '%' . $global . '%')
                ->orWhere('expenses.amount', 'like', '%' . $global . '%')
                ->orWhereHas('expense_details.chart_of_account', function ($query) use ($global) {
                    $query->where('name', 'like', '%' . $global . '%');
                });
        });
    }
}