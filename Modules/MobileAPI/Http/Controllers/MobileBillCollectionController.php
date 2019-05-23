<?php

namespace Modules\MobileAPI\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\BillCollection;
use Modules\Billing\Entities\Customer;
use JWTAuth;

class MobileBillCollectionController extends Controller
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
//        $collections = $request->all();
//        dd($request->all());
        $collections = json_decode($request->data);
//        dd($collections);
//        return response()->json($collections);

        foreach ($collections as $collection) {
//            return response()->json($collection->id);
            $customer = Customer::find($collection->customer_id);
            $bill_collection = new BillCollection([
                'user_id' => $user->id,
                'no_of_months' => $collection->num_of_months,
                'total' => $collection->total,
                'discount' => 0,
                'due_on' => $customer->due_on,
                'lat' => $collection->lat,
                'lon' => $collection->lon
            ]);
            $customer->bill_collections()->save($bill_collection);
            $updated_due_on = (new Carbon($customer->due_on))->addMonths($collection->num_of_months);
            $customer->update(['due_on' => $updated_due_on]);
        }

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Customer "%s" created successfully', $customer->name)
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
