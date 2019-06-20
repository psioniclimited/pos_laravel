<?php

namespace Modules\Sales\Http\Controllers;

use App\Filters\OrderFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Order;
use DB;
use Modules\Sales\Entities\OrderDetail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param OrderFilter $filter
     * @return Response
     */
    public function index(Request $request, OrderFilter $filter)
    {
//        this.order.total - (this.order.total * this.order.discount) / 100;
        $order = Order::filter($filter)
            ->join('clients', 'clients.id', 'orders.client_id')
            ->select(
                'orders.id',
                'orders.date',
                'clients.name',
                'orders.total',
                'orders.discount',
                DB::raw('(orders.total - (orders.total * orders.discount)/100) as grand_total')
            )
            ->paginate($request->per_page);
        return response()->json($order);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('sales::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @param $id
     * @return Response
     */
    public function show($id)
    {
//        $order = Order::with('order_details')
//            ->with('order_details.product')
//            ->with('order_details.option')
//            ->with('client')
//            ->where('id', $id)
//            ->first();
//        return response()->json($id);

//        $orderDetails = OrderDetail::with('addon')
//            ->with('product')
//            ->with('option')
//            ->with('order.client')
//            ->where('order_id', '=', $id)->get();

        $orderDetails = OrderDetail::leftJoin('addon_order_detail','addon_order_detail.order_detail_id','order_details.id')
            ->leftJoin('addons','addons.id','addon_order_detail.addon_id')
            ->leftJoin('options','options.id','order_details.option_id')
            ->Join('products','products.id','order_details.product_id')
            ->groupBy(
                'addons.id',
                'products.id'
                )
            ->where('order_details.order_id', $id)
            ->select(
                'addons.name AS addon_name',
                'addons.price AS addon_price',
                'products.name AS product_name',
                'products.sale_price AS product_price',
                'options.type AS option_type',
                'options.price AS option_price',
                'order_details.quantity'
                )
            ->get();

//        array_push($orderDetails, $order);

        return response()->json($orderDetails);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('sales::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
