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
//        dd('asdasdsd');
//        dd($request->data);

        $user = JWTAuth::parseToken()->authenticate();
        $collections = json_decode($request->data);
        foreach ($collections as $order) {
            $newOrder = new Order([
                'date' => (new Carbon($order->date))->toDateString(),
                'user_id' => $user->id,
                'total' => $order->total,
                'discount' => $order->discount,
                'client_id' => $order->clientId,
                'company_id'=> $user->company_id
            ]);
            $newOrder->save();
            foreach ($order->orderDetails as $orderDetail) {
                $newOrderDetail = new OrderDetail([
                    'quantity'=> $orderDetail->quantity,
                    'product_id'=> $orderDetail->productId,
                    'option_id' => $orderDetail->optionId,
                    'order_id' => $newOrder->id,
                    'company_id'=> $user->company_id,
                    'total'=> $orderDetail->total
//                    if( $orderDetail->optionId != null){
//
//                    }
                ]);
                $newOrderDetail->save();

            }
        }

        return response()->json([
            'working'
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
