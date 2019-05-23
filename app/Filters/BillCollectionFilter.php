<?php

namespace App\Filters;

use Carbon\Carbon;

class BillCollectionFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['code', 'name', 'phone', 'area', 'date', 'collector', 'internet', 'global', 'sort_by'];

    /**
     * Filter the query by a given username.
     *
     * @param string $username
     * @return
     */
    protected function code($code)
    {
        return $this->builder->where('customers.code', 'like', '%' . $code . '%');
    }

    protected function name($name)
    {
        return $this->builder->where('customers.name', 'like', '%' . $name . '%');
    }

    protected function phone($phone)
    {
        return $this->builder->where('customers.phone', 'like', '%' . $phone . '%');
    }

    protected function area($area)
    {
        return $this->builder->whereHas('customer', function ($query) use ($area) {
            $query->where('area_id', $area);
        });
    }

    protected function date($date)
    {
        $dateArray = explode(',', $date);
        $dateArray[0] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[0])->format('Y-m-d');
        if (sizeof($dateArray) > 1 && !empty($dateArray[1])) {
            $dateArray[1] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[1])->format('Y-m-d');
            return $this->builder
                ->whereDate('bill_collections.created_at', '>=', $dateArray[0])
                ->whereDate('bill_collections.created_at', '<=', $dateArray[1]);
        } else {
            return $this->builder
                ->whereDate('bill_collections.created_at', '=', $dateArray[0]);
        }
    }

    protected function collector($collector)
    {
        return $this->builder->where('user_id', $collector);
    }

    protected function internet($internet)
    {
        if ($internet === 'true')
            return $this->builder->where('customers.subscription_type_id', '3');
        elseif ($internet === 'false')
            return $this->builder->where('customers.subscription_type_id', '!=', '3');

    }

    protected function sort_by($sort_by)
    {
        $sort_by = explode('.', $sort_by);
        $sort_by[1] == 1 ? $sort_by[1] = 'asc' : $sort_by[1] = 'desc';
//        $this->builder->join('customers', 'bill_collections.customer_id', '=', 'customers.id');
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {
        return $this->builder->where(function ($query) use ($global) {
            $query->where('bill_collections.total', 'like', '%' . $global . '%')
                ->orWhere('bill_collections.discount', 'like', '%' . $global . '%')
                ->orWhere('bill_collections.created_at', 'like', '%' . $global . '%')
                ->orWhere('customers.name', 'like', '%' . $global . '%')
                ->orWhere('customers.code', 'like', '%' . $global . '%')
                ->orWhere('customers.phone', 'like', '%' . $global . '%')
                ->orWhere('areas.name', 'like', '%' . $global . '%')
                ->orWhere('users.name', 'like', '%' . $global . '%');
//                ->orWhereRaw('(bill_collections.total - bill_collections.discount) like ?', '%' . $global . '%');
        });

    }
}