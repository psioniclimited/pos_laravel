<?php

namespace Modules\Billing\Http\Controllers;

use App\Filters\BillCollectionFilter;
use App\Filters\CustomerFilter;
use App\Filters\FeeCollectionFilter;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\Billing\Entities\BillCollection;
use Modules\Billing\Entities\Customer;
use DB;
use Modules\Billing\Entities\FeeCollection;

class TotalController extends Controller
{
    function due(CustomerFilter $filter)
    {
        $next_month = Carbon::now()->addMonth()->format('Y-m-01');

        $due = Customer::filter($filter)
            ->where('due_on', '<', $next_month)
            ->where('status', 1)
            ->selectRaw('sum((timestampdiff(MONTH, customers.due_on, ?) * customers.monthly_bill)) as total', [$next_month])
            ->get();
        return response()->json($due);
    }

    function bill_collected(BillCollectionFilter $filter)
    {
        $sum = BillCollection::filter($filter)
            ->join('customers', 'customers.id', 'bill_collections.customer_id')
            ->join('areas', 'areas.id', 'customers.area_id')
            ->join('users', 'users.id', 'bill_collections.user_id')
            ->selectRaw('sum(total - discount) as total')
            ->get();
        return response()->json($sum);
    }

    function fee_collected(FeeCollectionFilter $filter)
    {
        $sum = FeeCollection::filter($filter)
            ->join('customers', 'customers.id', 'fee_collections.customer_id')
            ->join('fee_types', 'fee_types.id', 'fee_collections.fee_type_id')
            ->join('areas', 'areas.id', 'customers.area_id')
            ->selectRaw('sum(total) as total')
            ->get();
        return response()->json($sum);
    }
}
