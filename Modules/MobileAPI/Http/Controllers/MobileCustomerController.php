<?php

namespace Modules\MobileAPI\Http\Controllers;

use App\Filters\CustomerFilter;
use App\Filters\MobileCustomerFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Billing\Entities\Customer;
use Modules\Billing\Http\Requests\CustomerRequest;
use JWTAuth;
use Modules\MobileAPI\Entities\MobileCustomer;

class MobileCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, MobileCustomerFilter $filter)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $customer = MobileCustomer::filter($filter)
            ->leftJoin('customer_user', 'customer_user.customer_id', 'customers.id')
            ->groupBy(
                'customers.id'
            )
            ->select(
                'customers.id',
                'customers.code',
                'customers.name',
                'customers.address',
                'customers.due_on',
                'customers.monthly_bill',
                'customers.created_at',
                'customers.updated_at'
            )
            ->where('customer_user.user_id', $user->id)
            ->paginate($request->per_page);
        return response()->json($customer);
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
