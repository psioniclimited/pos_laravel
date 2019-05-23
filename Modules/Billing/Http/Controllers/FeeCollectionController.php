<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\FeeCollectionFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\FeeCollection;
use Modules\Billing\Http\Requests\FeeCollectionRequest;
use JWTAuth;

class FeeCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FeeCollectionFilter $filter)
    {
        $fee_collection = FeeCollection::filter($filter)
            ->join('customers', 'customers.id', '=', 'fee_collections.customer_id')
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('fee_types', 'fee_types.id', '=', 'fee_collections.fee_type_id')
            ->select(
                'fee_collections.id',
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'fee_collections.created_at',
                'fee_types.name as fee_type',
                'fee_collections.total')
            ->paginate($request->per_page);
        return response()->json($fee_collection);
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
    public function store(FeeCollectionRequest $request)
    {
        $fee_collections = $request->all();
        foreach ($fee_collections as $fee_collection) {
            FeeCollection::create(
                [
                    'customer_id' => $fee_collection['customer_id'],
                    'fee_type_id' => $fee_collection['fee_type_id'],
                    'total' => $fee_collection['total'],
                ]
            );
        }
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Fee Collection successful', $fee_collection)
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
