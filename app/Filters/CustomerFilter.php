<?php

namespace App\Filters;

use Carbon\Carbon;

class CustomerFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['code', 'name', 'phone', 'area', 'address', 'collector', 'subscription_type', 'status', 'select', 'internet', 'sort_by', 'last_id', 'gt_updated_at', 'has_due', 'global'];

    /**
     * Filter the query by a given username.
     *
     * @param string $username
     * @return
     */

    protected function code($code)
    {
        return $this->builder->where('code', 'like', '%' . $code . '%');
    }

    protected function name($name)
    {
        return $this->builder->where('customers.name', 'like', '%' . $name . '%');
    }

    protected function phone($phone)
    {
        return $this->builder->where('phone', 'like', '%' . $phone . '%');
    }

    protected function address($address)
    {
        return $this->builder->where('address', 'like', '%' . $address . '%');
    }

    protected function area($area)
    {
        return $this->builder->where('area_id', $area);
    }

    protected function subscription_type($subscription_type)
    {
        return $this->builder->where('subscription_type_id', $subscription_type);
    }

    protected function status($status)
    {
        return $this->builder->where('status', $status);
    }

    protected function select($select)
    {
        return $this->builder->where('customers.name', 'like', '%' . $select . '%')
            ->orWhere('code', 'like', '%' . $select . '%')
            ->orWhere('phone', 'like', '%' . $select . '%');
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
        return $this->builder->orderBy($sort_by[0], $sort_by[1]);
    }

    protected function global($global)
    {
        $this->builder->where(function ($query) use ($global) {
            $query->where('customers.name', 'like', '%' . $global . '%')
                ->orWhere('code', 'like', '%' . $global . '%')
                ->orWhere('phone', 'like', '%' . $global . '%')
                ->orWhereHas('area', function ($query) use ($global) {
                    $query->where('name', 'like', '%' . $global . '%');
                })
                ->orWhere('address', 'like', '%' . $global . '%')
                ->orWhereHas('subscription_type', function ($query) use ($global) {
                    $query->where('name', 'like', '%' . $global . '%');
                })
                ->orWhere('status', 'like', '%' . $global . '%')
                ->orWhereHas('users', function ($query) use ($global) {
                    $query->where('name', 'like', '%' . $global . '%');
                })
                ->orWhere('monthly_bill', 'like', '%' . $global . '%');
//                ->orWhereRaw('IF(customers.due_on <= CURDATE(), (customers.monthly_bill * (TIMESTAMPDIFF(MONTH, customers.due_on, DATE_FORMAT(NOW() ,"%Y-%m-01")) + 1)), "0") like ?', '%' . $global . '%');
        });


        return $this->builder;
    }

//    protected function code($code){
//        return $this->builder->where('code',  $code);
//    }
    protected function last_id($last_id)
    {
        return $this->builder->where('id', '>', $last_id);
    }

    protected function has_due($has_due)
    {
        return $this->builder->where('due_on', '<', (Carbon::now())->startOfMonth())
            ->where('customers.status', '1');
    }

    protected function collector($collector)
    {
//        return $this->builder->where('users.name', 'like', '%' . $collector . '%');
        return $this->builder->whereHas('users', function ($query) use ($collector) {
            $query->where('id', $collector);
        });
    }

    protected function gt_updated_at($gt_updated_at)
    {
        return $this->builder->whereDate('updated_at', '>', $gt_updated_at);
    }
}