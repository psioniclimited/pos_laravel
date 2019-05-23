<?php
/**
 * Created by PhpStorm.
 * User: shan
 * Date: 4/29/19
 * Time: 2:56 PM
 */

namespace App\Filters;


class ClientFilter extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['city' ,'global'];

    /**
     * Filter the query by a given username.
     *
     * @param string $username
     * @return
     */
    protected function city($city)
    {
        return $this->builder->where('city', 'like', '%' . $city . '%');
    }
}