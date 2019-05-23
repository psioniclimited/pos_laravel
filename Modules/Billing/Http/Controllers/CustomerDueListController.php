<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\CustomerDueListFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\Customer;
use Modules\Billing\Entities\CustomerDue;
use DB;

class CustomerDueListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, CustomerDueListFilter $filter)
    {
        $customer = CustomerDue::filter($filter)
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
            ->join('customer_user', 'customer_user.customer_id', 'customers.id')
            ->join('users', 'users.id', 'customer_user.user_id')
            ->groupBy(
                'customers.id',
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name',
                'customers.address',
                'customers.due_on',
                'customers.monthly_bill',
                'subscription_types.name',
                'customers.status'
            )
            ->select(
                'customers.id',
                'customers.code',
                'customers.name',
                DB::raw('GROUP_CONCAT(users.name SEPARATOR \', \') as users_name'),
                DB::raw('IF(customers.due_on <= CURDATE(), (customers.monthly_bill * (TIMESTAMPDIFF(MONTH, customers.due_on, DATE_FORMAT(NOW() ,"%Y-%m-01")) + 1)), "0") as total_due'),
                'customers.phone',
                'areas.name as area',
                'customers.address',
                'customers.due_on',
                'customers.monthly_bill',
                'subscription_types.name as subscription_type',
                'customers.status'
            )
            ->where('due_on', '<=', (Carbon::now())->startOfMonth())
            ->where('customers.status', '1')
            ->where('subscription_type_id', '!=', '3')
            ->paginate($request->per_page);
        return response()->json($customer);
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
    public function store(Request $request)
    {
    }

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
