<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\FeeTypeFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\FeeCollection;
use Modules\Billing\Entities\FeeType;

class FeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FeeTypeFilter $filter)
    {
        $feeType = FeeType::filter($filter)
            ->paginate($request->per_page);
        return response()->json($feeType);
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
        $feeType = FeeType::create($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Fee Type "%s" created successfully', $feeType->name)
                ]
        ]);    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(FeeType $feeType)
    {
        return response()->json($feeType);
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
    public function update(FeeType $feeType, Request $request)
    {
        $feeType->update($request->all());

        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Fee Type "%s" updated successfully', $feeType->name)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(FeeType $feeType)
    {
        if (FeeCollection::where('fee_type_id', $feeType->id)->exists()) {
            return response()->json(['errors' => ['fee_type' => [sprintf('Cannot delete fee of type "%s" already collected', $feeType->name)]]], 422);
        }
        else{
            $feeType->delete();

            return response()->json([
                'delete' =>
                    [
                        'message' => sprintf('Fee Type "%s" deleted successfully', $feeType->name)
                    ]
            ]);
        }
    }
}
