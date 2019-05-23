<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\BillCollectionFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\BillCollection;
use Modules\Billing\Entities\Customer;
use JWTAuth;
use Modules\Billing\Entities\CustomerBillDetail;
use Modules\Billing\Http\Requests\BillCollectionRequest;
use DB;

class BillCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, BillCollectionFilter $filter)
    {
//        $bill_collection = BillCollection::filter($filter)
//            ->join('customer')
//            ->paginate($request->per_page);
//        return response()->json($bill_collection);

        $bill_collection = BillCollection::filter($filter)
            ->join('customers', 'customers.id', '=', 'bill_collections.customer_id')
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('users', 'users.id', '=', 'bill_collections.user_id')
            ->select(
                'bill_collections.id',
                'bill_collections.customer_id',
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'customers.shared',
                'customers.ppoe',
                'customers.bandwidth',
                'bill_collections.no_of_months',
                'bill_collections.created_at',
                'users.name as collector',
                'bill_collections.total',
                'bill_collections.discount',
                'bill_collections.due_on',
                'bill_collections.latest',
                'bill_collections.lat',
                'bill_collections.lon',
                DB::raw('(bill_collections.total - bill_collections.discount) as grand_total')
                )
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page);
        return response()->json($bill_collection);
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
    public function store(BillCollectionRequest $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $bill_collections = $request->all();
        foreach ($bill_collections as $bill_collection) {
            $customer = Customer::find($bill_collection['customer']['id']);
            $due_on = (new Carbon($customer->due_on))->addMonth($bill_collection['no_of_months']);
//            }

            $request->request->add(['user_id' => $user->id]);
            BillCollection::create(                                   // creates new bill collection
                [
                    'user_id' => $user->id,
                    'customer_id' => $customer->id,
                    'no_of_months' => $bill_collection['no_of_months'],
                    'total' => $bill_collection['total'],
                    'discount' => $bill_collection['discount'],
                    'due_on' => $customer->due_on,
                ]
            );

            $customer->update(['due_on' => $due_on->startOfMonth()]); // updates due on

        }

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Bill Collection successful')
                ]
        ]);
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
