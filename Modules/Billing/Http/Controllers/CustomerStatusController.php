<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\CustomerStatusFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\CustomerStatus;
use Modules\Billing\Http\Requests\CustomerStatusRequest;

class CustomerStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, $customer_id, CustomerStatusFilter $filter)
    {
        $customer_status = CustomerStatus::filter($filter)
            ->where('customer_id', $customer_id)
            ->paginate($request->per_page);
        return response()->json($customer_status);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('billing::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CustomerStatusRequest $request, $customer_id)
    {
        $customerStatus = CustomerStatus::create(array_merge($request->all(), ['customer_id' => $customer_id]));
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Customer Status created successfully', $customerStatus)
                ]
        ]);    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('billing::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('billing::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(CustomerStatus $customer_status, Request $request)
    {
        $customer_status->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Customer Status updated successfully', $customer_status)
                ]
        ]);
    }


    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
