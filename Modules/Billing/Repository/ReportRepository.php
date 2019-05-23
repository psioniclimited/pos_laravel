<?php

namespace Modules\Billing\Repository;

use App\Filters\CollectorRankingFilter;
use App\Filters\UserFilter;
use Carbon\Carbon;
use Modules\Accounting\Entities\Expense;
use Modules\Billing\Entities\Area;
use Modules\Billing\Entities\BillCollection;
use DB;
use Modules\Billing\Entities\Customer;
use Modules\User\Entities\Company;
use Modules\User\Entities\TenantUser;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportRepository
{
    public function totalOfLastThirtyDays()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $billCollectionTotals = DB::select(
        /** @lang text */
            "SELECT SUM(total) - SUM(discount) AS total, CAST(created_at AS DATE) as created_at
            FROM
                bill_collections
            WHERE
                created_at BETWEEN ? AND ?
                AND bill_collections.company_id = ?
                AND deleted_at IS NULL
            GROUP BY CAST(created_at AS DATE)
            ", [(new Carbon)->subDays(30)->toDateString(), (Carbon::now()->addDays(1))->toDateString(), $user->company_id]);
        return $billCollectionTotals;
    }

    public function billCollectionInTimeInterval($start, $end)
    {
        $total = BillCollection::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->sum('total');
        $discount = BillCollection::whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->sum('discount');
        return ($total - $discount);
    }

    public function areaTotal()
    {

        $total = BillCollection::join('customers', 'customers.id', '=', 'bill_collections.customer_id')
            ->groupBy('customers.area_id')
            ->select('customers.area_id', DB::raw('SUM(total) - SUM(discount) as total'))->get();

        return $total;
    }

    public function areaLabels()
    {
        $labels = Area::all();
        return $labels;
    }

    public function customerCount()
    {
        $customers = Customer::count();
        return $customers;
    }

    public function customerLimit()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $company = Company::find($user->company_id);

        $customer_limit = $company->pricing_plan->customer_limit;
        return $customer_limit;
    }

    public function customerDue()
    {
        $next_month = Carbon::now()->addMonth()->format('Y-m-01');

        $due = Customer::where('due_on', '<', $next_month)
            ->where('status', 1)
            ->selectRaw('sum((timestampdiff(MONTH, customers.due_on, ?) * customers.monthly_bill)) as total', [$next_month])
            ->get();
        return $due;
    }

    public function totalExpenses()
    {
        $total = Expense::sum('amount');
        return $total;
    }

    public function billCollectorRanking($start, $end, $request)
    {
        $collectors = TenantUser::leftJoin('bill_collections', function ($join) use ($start, $end) {
            $join->on('bill_collections.user_id', 'users.id');
            $join->on('bill_collections.created_at', '>=', DB::raw("'$start'"));
            $join->on('bill_collections.created_at', '<=', DB::raw("'$end'"));
//                $join->isNull('bill_collections.deleted_at');
        })
            ->where('bill_collections.deleted_at', null)
            ->groupBy('users.id', 'users.name')
            ->select(
                'users.id',
                'users.name as collector',
                DB::raw("SUM(bill_collections.total - bill_collections.discount) as collected")
            )
            ->orderBy('collected', 'desc')
            ->paginate($request->per_page);
        return response()->json($collectors);
    }

    public function connectedCustomers($start, $end, $request)
    {
        $connectedCustomers = Customer::join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
            ->where('customers.status', 1)
            ->where('customers.created_at', '>=', $start)
            ->where('customers.created_at', '<=', $end)
            ->select(
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'customers.address',
                'subscription_types.name as subscription_type',
                'customers.created_at'
            )
            ->paginate($request->per_page);
        return response()->json($connectedCustomers);
    }

    public function disconnectedCustomers($start, $end, $request)
    {
        $disconnectedCustomers = Customer::join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
            ->where('customers.status', 0)
            ->where('customers.updated_at', '>=', $start)
            ->where('customers.updated_at', '<=', $end)
            ->select(
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'customers.address',
                'subscription_types.name as subscription_type'
            )
            ->paginate($request->per_page);
        return response()->json($disconnectedCustomers);
    }

    public function targetBill()
    {
        $targetBill = Customer::where('customers.status', 1)
            ->sum('monthly_bill');
        return $targetBill;
    }
}
