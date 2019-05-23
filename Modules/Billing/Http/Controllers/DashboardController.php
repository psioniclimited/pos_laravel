<?php

namespace Modules\Billing\Http\Controllers;

use App\Mail\SignedUp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Billing\Entities\BillCollection;
use Modules\Billing\Repository\ReportRepository;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ReportRepository $reportRepository, Request $request)
    {

        $billCollections = [
            'customers' => $reportRepository->customerCount(),
            'customerLimit' => $reportRepository->customerLimit(),
            'customerDue' => $reportRepository->customerDue(),
            'totalExpense' => $reportRepository->totalExpenses(),
            'dailyBillCollections' => $reportRepository->totalOfLastThirtyDays(),
            'areaLabels' => $reportRepository->areaLabels(),
            'areaCollection' => $reportRepository->areaTotal(),
            'thisMonthCollection' => $reportRepository->billCollectionInTimeInterval((new Carbon)->startOfMonth()->toDateString(), (new Carbon)->endOfMonth()->toDateString()),
            'lastMonthCollection' => $reportRepository->billCollectionInTimeInterval((new Carbon)->startOfMonth()->subMonth()->toDateString(), (new Carbon)->endOfMonth()->subMonth()->toDateString()),
            'secondLastMonthCollection' => $reportRepository->billCollectionInTimeInterval((new Carbon)->startOfMonth()->subMonths(2)->toDateString(), (new Carbon)->endOfMonth()->subMonths(2)->toDateString()),
            'connectionData' => $reportRepository->connectedCustomers((new Carbon)->startOfMonth()->toDateString(), (new Carbon)->endOfMonth()->toDateString(), $request)
        ];

        return response()->json($billCollections);
    }

    public function mail()
    {
        Mail::to('saadbinmahbub@gmail.com')->send(new SignedUp());
        dd('test');
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
