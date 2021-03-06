<?php

namespace Modules\Billing\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Billing\Repository\ReportRepository;

class ConnectedCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ReportRepository $reportRepository, Request $request)
    {
        $connectedCustomers = [
            'connectedCustomers' => $reportRepository->connectedCustomers((new Carbon)->startOfMonth()->toDateString(), (new Carbon)->endOfMonth()->toDateString(), $request),
            'disconnectedCustomers' => $reportRepository->disconnectedCustomers((new Carbon)->startOfMonth()->toDateString(), (new Carbon)->endOfMonth()->toDateString(), $request),
        ];

        return response()->json($connectedCustomers);
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
