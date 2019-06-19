<?php

namespace Modules\MobileAPI\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Order;
use Modules\Sales\Entities\OrderDetail;
use JWTAuth;
use Carbon\Carbon;

class MobileOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('mobileapi::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('mobileapi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $collections = json_decode($request->data);
//        return response()->json($collections);
//        $collections = $request->data;
        foreach ($collections as $order) {
            $newOrder = Order::create([
                'date' => $order->date,
                'user_id' => $user->id,
                'total' => $order->total,
                'discount' => $order->discount,
                'client_id' => $order->clientId,
            ]);
            foreach ($order->orderDetails as $orderDetail) {
                $order_detail = $newOrder->order_details()->create([
                    'quantity' => $orderDetail->quantity,
                    'product_id' => $orderDetail->productId,
                    'option_id' => $orderDetail->optionId,
                    'total' => $orderDetail->total * $orderDetail->quantity
                ]);

                foreach ($orderDetail->addon as $addon) {
                    $order_detail->addon()->attach($addon);
                }

            }
        }
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Order Synced Successfully')
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('mobileapi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('mobileapi::edit');
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
