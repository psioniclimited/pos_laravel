<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Billing\Entities\Customer;
use Modules\Billing\Repository\ReportRepository;
use Modules\User\Entities\TenantUser;
use JWTAuth;
use DB;

class MonthlyReportExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $reportRepository = new ReportRepository();
        $authUser = JWTAuth::parseToken()->authenticate();
        $start = (new Carbon)->startOfMonth()->toDateString();
        $end = (new Carbon)->endOfMonth()->toDateString();

        $data = [
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
            'user' => TenantUser::where('id', $authUser->id)
                ->first(),
            'connectedCustomers' => Customer::join('areas', 'areas.id', '=', 'customers.area_id')
                ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
                ->where('customers.status', 1)
                ->where('customers.created_at', '>=', (new Carbon)->startOfMonth()->toDateString())
                ->where('customers.created_at', '<=', (new Carbon)->endOfMonth()->toDateString())
                ->count(),
            'disconnectedCustomers' => Customer::join('areas', 'areas.id', '=', 'customers.area_id')
                ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
                ->where('customers.status', 0)
                ->where('customers.updated_at', '>=', (new Carbon)->startOfMonth()->toDateString())
                ->where('customers.updated_at', '<=', (new Carbon)->endOfMonth()->toDateString())
                ->count(),
            'targetBill' => $reportRepository->targetBill(),
            'thisMonthRanking' => TenantUser::leftJoin('bill_collections', function ($join) use ($start, $end) {
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
                ->orderBy('collected', 'desc')->get(),
            'generatedDateLabel' => Carbon::now()->toDateTimeString(),
            'currentMonthLabel' => Carbon::today()->format('F, Y')
        ];

        return $data;
    }
}
