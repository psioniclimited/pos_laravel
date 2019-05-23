<?php

namespace Modules\Accounting\Http\Controllers;

use App\Filters\ChartOfAccountFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\ChartOfAccount;
use Modules\Accounting\Entities\ExpenseDetail;
use Modules\Accounting\Http\Requests\ChartOfAccountRequest;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, ChartOfAccountFilter $filter)
    {
        $chartOfAccount = ChartOfAccount::filter($filter)->get();
        return response()->json($chartOfAccount);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('accounting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ChartOfAccountRequest $request, $parent_id)
    {
        $latestChartOfAccount = ChartOfAccount::where('parent_account_id', $parent_id)->orderBy('code', 'desc')->first();
        $chartOfAccount = ChartOfAccount::create
        (
            array_merge($request->all(),
                [
                    'parent_account_id' => $parent_id,
                    'code' => ++$latestChartOfAccount->code
                ]
            )
        );
        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Chart Of Account "%s" created successfully', $chartOfAccount->name)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('accounting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('accounting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ChartOfAccountRequest $request, ChartOfAccount $chart_of_account)
    {
        $chart_of_account->update($request->all());
        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Chart Of Account "%s" updated successfully', $chart_of_account->name)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        if (ExpenseDetail::where('chart_of_account_id', $chartOfAccount->id)->exists()) {
            return response()->json(['errors' => ['expense_type' => [sprintf('Cannot delete expense type "%s"', $chartOfAccount->name)]]], 422);
        }
        else{
//            return response()->json('delete');
            $chartOfAccount->delete();

            return response()->json([
                'delete' =>
                    [
                        'message' => sprintf('Chart Of Account "%s" deleted successfully', $chartOfAccount->name)
                    ]
            ]);
        }
    }
}
