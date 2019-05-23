<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\CustomerFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB;
use Modules\Billing\Entities\Customer;
use Modules\Billing\Http\Requests\CustomerRequest;
use JWTAuth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, CustomerFilter $filter)
    {
        $customer = Customer::filter($filter)
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
            ->leftJoin('customer_user', 'customer_user.customer_id', 'customers.id')
            ->leftJoin('users', 'users.id', 'customer_user.user_id')
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
     * @param Request $request
     * @return Response
     */
    public function store(CustomerRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (Customer::where('code', $request->code)->where('company_id', $user->company_id)->exists()) {
            return response()->json([
                'errors' =>
                    [
                        'code' => [sprintf('Customer Code already exists')]
                    ]
            ], 422);
        }

        $users = collect(array_pluck($request->only('users')['users'], 'id'));

        $customer = Customer::create([
            'name' => $request->name,
            'code' => $request->code,
            'email' => $request->email,
            'phone' => $request->phone,
            'nid' => $request->nid,
            'due_on' => $request->due_on,
            'area_id' => $request->area_id,
            'subscription_type_id' => $request->subscription_type_id,
            'address' => $request->address,
            'monthly_bill' => $request->monthly_bill,
            'status' => $request->status
        ]);

        $customer->users()->sync($users->filter());
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
    public function show($id)
    {
        $customer = Customer::with('subscription_type')
            ->with('area')
            ->with('users')
            ->where('id', $id)
            ->first();
        return response()->json($customer);
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
     * @param Request $request
     * @return Response
     */
    public function update(Customer $customer, CustomerRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $customerCodeExists = Customer::where('code', $request->code)->where('company_id', $user->company_id)->exists();
        if (
            !($customer->code === $request->code) &&
            $customerCodeExists
        ) {
            return response()->json([
                'errors' =>
                    [
                        'code' => [sprintf('Customer Code already exists')]
                    ]
            ], 422);
        }

        $users = collect(array_pluck($request->only('users')['users'], 'id'));
        $customer->update($request->only([
            'name',
            'email',
            'code',
            'phone',
            'nid',
            'due_on',
            'area_id',
            'subscription_type_id',
            'address',
            'monthly_bill',
            'status'
        ]));
        $customer->users()->sync($users->filter());

        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Customer "%s" updated successfully', $customer->name)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'delete' =>
                [
                    'message' => sprintf('Customer "%s" deleted successfully', $customer->name)
                ]
        ]);
    }
}
