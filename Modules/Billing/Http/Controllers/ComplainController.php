<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\ComplainFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\Complain;
use Modules\Billing\Http\Requests\ComplainRequest;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, ComplainFilter $filter)
    {
        $complain = Complain::filter($filter)
            ->join('customers', 'customers.id', '=', 'complains.customer_id')
            ->join('complain_statuses', 'complain_statuses.id', '=', 'complains.complain_status_id')
            ->select( 'complains.id', 'complains.date', 'complains.description', 'complain_statuses.name as status_name', 'customers.code', 'customers.name as customer_name', 'customers.phone')
            ->paginate($request->per_page);
        return response()->json($complain);
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
    public function store(ComplainRequest $request)
    {
        $complain = Complain::create($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Complain created successfully', $complain)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $complain = Complain::where('complains.id', $id)
            ->with('customer')
            ->with('complain_status')
            ->first();
        return response()->json($complain);
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
    public function update(ComplainRequest $request, Complain $complain)
    {
        $complain->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Complain updated successfully', $complain)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Complain $complain)
    {
        $complain->delete();

        return response()->json([
            'delete' =>
                [
                    'message' => sprintf('Complain deleted successfully', $complain)
                ]
        ]);
    }
}
