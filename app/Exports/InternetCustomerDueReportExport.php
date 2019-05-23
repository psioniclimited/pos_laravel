<?php

namespace App\Exports;

use App\Filters\CustomerDueListFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Modules\Billing\Entities\CustomerDue;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use DB;

class InternetCustomerDueReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    protected $filter;

    function __construct(Request $request)
    {
        $this->filter = new CustomerDueListFilter($request);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return CustomerDue::filter($this->filter)
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
            ->join('customer_user', 'customer_user.customer_id', 'customers.id')
            ->join('users', 'users.id', 'customer_user.user_id')
            ->where('customers.due_on', '<=', (new Carbon('first day of this month'))->startOfDay())
            ->where('customers.status', '1')
            ->where('customers.subscription_type_id', '3')
            ->groupBy(
                'customers.id',
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name',
                'customers.address',
                'customers.shared',
                'customers.ppoe',
                'customers.bandwidth',
                'customers.due_on',
                'customers.monthly_bill'
            )
            ->select(
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as areas',
                'customers.address',
                'customers.shared',
                'customers.ppoe',
                'customers.bandwidth',
                DB::raw('GROUP_CONCAT(users.name SEPARATOR \', \') as users_name'),
                'customers.due_on',
                'customers.monthly_bill',
                DB::raw('IF(customers.due_on <= CURDATE(), (customers.monthly_bill * (TIMESTAMPDIFF(MONTH, customers.due_on, DATE_FORMAT(NOW() ,"%Y-%m-01")) + 1)), "0") as total_due')
            )
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'CODE',
            'NAME',
            'PHONE',
            'AREA',
            'ADDRESS',
            'COLLECTORS',
            'DUE ON',
            'MONTHLY BILL',
            'DUE',
        ];
    }

    public function csvHeadings(): array
    {
        return [
            'code',
            'name',
            'phone',
            'area',
            'address',
            'shared',
            'ppoe',
            'bandwidth',
            'users_name',
            'due_on',
            'monthly_bill',
            'total_due'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
//            'B' => Alignment::HORIZONTAL_CENTER,
//            'D' => Alignment::HORIZONTAL_CENTER,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(0);
                $event->sheet->getDelegate()->getPageSetup()->getFitToWidth();
            },
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:J1'; // All headers
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                        ],
                    ]
                ];
                $event->sheet->getDelegate()->getPageSetup()->setFitToWidth(0);
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(5);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(10);

            },
        ];
    }
}
