<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\SubscriptionTypeFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\SubscriptionType;

class SubscriptionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, SubscriptionTypeFilter $filter)
    {
        $subscription_type = SubscriptionType::filter($filter)
            ->paginate($request->per_page);
        return response()->json($subscription_type);
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
        $subscription_type = SubscriptionType::create($request->all());
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Subscription Type "%s" created successfully', $subscription_type->name)
                ]
        ]);    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(SubscriptionType $subscription_type)
    {
        return response()->json($subscription_type);
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
    public function update(SubscriptionType $subscription_type, Request $request)
    {
        $subscription_type->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Subscription Type "%s" updated successfully', $subscription_type->name)
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
