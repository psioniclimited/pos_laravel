<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\BillCollectionFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\BillCollection;
use Modules\Billing\Entities\Customer;
use DB;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, BillCollectionFilter $filter)
    {
        $bill_collection = BillCollection::onlyTrashed()
            ->filter($filter)
            ->join('customers', 'customers.id', '=', 'bill_collections.customer_id')
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('users', 'users.id', '=', 'bill_collections.user_id')
            ->select(
                'bill_collections.id',
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'customers.shared',
                'customers.ppoe',
                'customers.bandwidth',
                'bill_collections.no_of_months',
                'bill_collections.deleted_at',
                'users.name as collector',
                'bill_collections.total',
                'bill_collections.discount',
                'bill_collections.due_on',
                'bill_collections.latest',
                DB::raw('(bill_collections.total - bill_collections.discount) as grand_total')
            )
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
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function store(BillCollection $bill_collection)
    {
        $customer = Customer::find($bill_collection['customer_id']); // grab customer related to bill
        $due_on = new Carbon($bill_collection->due_on); //grab due_on of latest bill
        $bill_collection->delete(); // soft delete refunded bill

        $customer->update(['due_on' => $due_on->startOfMonth()]); //update customer's due_on to latest bill's due_on

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Refund successful', $customer)
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
     * @param Request $request
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
