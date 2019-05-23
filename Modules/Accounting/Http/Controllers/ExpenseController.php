<?php

namespace Modules\Accounting\Http\Controllers;

use App\Filters\ExpenseFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\Expense;
use Modules\Accounting\Entities\ExpenseDetail;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, ExpenseFilter $filter)
    {
        $expense = Expense::filter($filter)
            ->with('paid_with')
            ->with('expense_details.chart_of_account')
            ->join('expense_details', 'expenses.id', 'expense_details.expense_id')
            ->join('chart_of_accounts', 'expense_details.chart_of_account_id', 'chart_of_accounts.id')
            ->join('chart_of_accounts as coa', 'expenses.paid_with_id', 'coa.id')

            ->select(
                'expenses.id',
                'expenses.date',
                'expenses.description',
                'chart_of_accounts.name as expense_type',
                'expenses.amount',
                'expenses.paid_with_id'
            )
            ->paginate($request->per_page);
        return response()->json($expense);
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
    public function store(Request $request)
    {
//        dd($request);
        $expenseDetails = collect($request->only('expense_details')['expense_details']);
        $expense = Expense::create($request->only([
            'date',
            'description',
            'amount',
            'paid_with_id'
        ]));
        foreach ($expenseDetails as $expenseDetail) {
            $expenseDetail['expenseCategory'] !== NULL ? $expense->expense_details()->create(
                [
                    'chart_of_account_id' => $expenseDetail['expenseCategory']['id'],
                    'amount' => $expenseDetail['splitAmount']
                ]
            ) : '';
        }

        return response()->json([
            'create' =>
                [
                    'message' => sprintf('Expense "%s" created successfully', $expense->description)
                ]
        ]);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $data = Expense::where('id', $id)->with('paid_with')->with('expense_details.chart_of_account')->get();
        return response()->json($data);
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
    public function update($id, Request $request)
    {
        $expenseDetails = collect($request->only('expense_details')['expense_details']);
        $expense = Expense::where('id', $id)->update($request->only([
            'description',
            'amount',
            'paid_with_id'
        ]));
        Expense::where('id', $id)->update(['date' => Carbon::parse($request->date)->format('Y-m-d')]);

        foreach ($expenseDetails as $expenseDetail) {
            $expenseDetail['expenseCategory'] !== NULL ? Expense::where('id', $id)->expense_details()->update( // error in expense_details()
                [
                    'chart_of_account_id' => $expenseDetail['expenseCategory']['id'],
                    'amount' => $expenseDetail['splitAmount']
                ]
            ) : "";
        }

        return response()->json([
            'update' =>
                [
                    'message' => sprintf('Expense "%s" updated successfully', $expense->description)
                ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'delete' =>
                [
                    'message' => sprintf('Expense "%s" deleted successfully', $expense->description)
                ]
        ]);
    }
}
