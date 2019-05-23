<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\AreaFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\Area;
use Modules\Billing\Entities\Customer;
use Modules\Billing\Http\Requests\AreaRequest;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, AreaFilter $filter)
    {
        $area = Area::filter($filter)
            ->paginate($request->per_page);
        return response()->json($area);
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
    public function store(AreaRequest $request)
    {
        $area = Area::create($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Area "%s" created successfully', $area->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Area $area)
    {
        return response()->json($area);
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
    public function update(Area $area, AreaRequest $request)
    {
        $area->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Area "%s" updated successfully', $area->name)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Area $area)
    {
        if (Customer::where('area_id', $area->id)->exists()) {
            return response()->json(['errors' => ['area' => [sprintf('Cannot delete area "%s"', $area->name)]]], 422);
        }
        else{
            $area->delete();

            return response()->json([
                'delete' =>
                    [
                        'message' => sprintf('Area "%s" deleted successfully', $area->name)
                    ]
            ]);
        }
    }
}
