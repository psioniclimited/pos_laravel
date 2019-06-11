<?php

namespace Modules\Sales\Http\Controllers;

use App\Filters\OrderFilter;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Order;

class TotalSalesController extends Controller
{
    function total_paid(OrderFilter $filter)
    {
        $sum = Order::filter($filter)
            ->join('clients', 'clients.id', 'orders.client_id')
            ->selectRaw('sum(orders.total - (orders.total * orders.discount)/100) as total')
            ->get();
        return response()->json($sum);
    }
}
