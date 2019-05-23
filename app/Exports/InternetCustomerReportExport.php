<?php

namespace App\Exports;

use App\Filters\CustomerFilter;
use function foo\func;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Modules\Billing\Entities\Customer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use DB;

class InternetCustomerReportExport implements FromCollection, WithHeadings, WithColumnFormatting, WithEvents
{
    protected $filter;

    function __construct(Request $request)
    {
        $this->filter = new CustomerFilter($request);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection()
    {
        return Customer::filter($this->filter)
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('subscription_types', 'subscription_types.id', '=', 'customers.subscription_type_id')
            ->join('customer_user', 'customer_user.customer_id', 'customers.id')
            ->join('users', 'users.id', 'customer_user.user_id')
            ->where('customers.subscription_type_id', '3')
            ->groupBy(
                'customers.id',
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name',
                'customers.address',
                'customers.due_on',
                'customers.monthly_bill',
                'subscription_types.name',
                'customers.status',
                'customers.shared',
                'customers.ppoe',
                'customers.bandwidth'
            )
            ->select(
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'customers.address',
                'subscription_types.name as subscription_type',
                'customers.status',
                DB::raw('GROUP_CONCAT(users.name SEPARATOR \', \') as users_name'),
                'customers.due_on',
                'customers.monthly_bill',
                'customers.shared',
                'customers.ppoe',
                'customers.bandwidth',
                DB::raw('IF(customers.due_on <= CURDATE(), (customers.monthly_bill * (TIMESTAMPDIFF(MONTH, customers.due_on, DATE_FORMAT(NOW() ,"%Y-%m-01")) + 1)), "0") as total_due')
            )->get();
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
            'TYPE',
            'STATUS',
            'COLLECTORS',
            'DUE ON',
            'MONTHLY BILL',
            'SHARED/DEDICATED',
            'PPOE',
            'BANDWIDTH',
            'DUE'
        ];
    }

    /**
     * @return array
     */
    public function csvHeadings(): array
    {
        return [
            'code',
            'name',
            'phone',
            'areas',
            'address',
            'shared',
            'ppoe',
            'bandwidth',
            'subscription_type',
            'status',
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
                $cellRange = 'A1:L1'; // All headers
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
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(10);


            },
        ];
    }
}
