<?php

namespace App\Exports;

use App\Filters\BillCollectionFilter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Billing\Entities\BillCollection;
use DB;

class BillCollectionReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filter;

    function __construct(Request $request)
    {
        $this->filter = new BillCollectionFilter($request);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $bill_collection = BillCollection::filter($this->filter)
            ->join('customers', 'customers.id', '=', 'bill_collections.customer_id')
            ->join('areas', 'areas.id', '=', 'customers.area_id')
            ->join('users', 'users.id', '=', 'bill_collections.user_id')
            ->where('customers.subscription_type_id', '!=', '3')
            ->select(
                'customers.code',
                'customers.name',
                'customers.phone',
                'areas.name as area',
                'bill_collections.no_of_months',
                'bill_collections.created_at',
                'users.name as collector',
                'bill_collections.total',
                'bill_collections.discount',
                DB::raw('(bill_collections.total - bill_collections.discount) as grand_total')
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
            'BILL MONTHS',
            'TIMESTAMP',
            'COLLECTED BY',
            'TOTAL BILL',
            'DISCOUNT',
            'PAID',
        ];
    }

    public function csvHeadings(): array
    {
        return [
            'code',
            'name',
            'phone',
            'area',
            'no_of_months',
            'created_at',
            'collector',
            'total',
            'discount',
            'grand_total'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                        ],
                    ]
                ];
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

            },
        ];
    }
}
