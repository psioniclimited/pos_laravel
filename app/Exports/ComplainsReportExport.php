<?php

namespace App\Exports;

use App\Filters\ComplainFilter;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Billing\Entities\Complain;

class ComplainsReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filter;
    function __construct(Request $request)
    {
        $this->filter = new ComplainFilter($request);
    }


    public function collection()
    {
        return $complain = Complain::filter($this->filter)
            ->join('customers', 'customers.id', '=', 'complains.customer_id')
            ->join('complain_statuses', 'complain_statuses.id', '=', 'complains.complain_status_id')
            ->select(
                'complains.date',
                'complains.description',
                'customers.code',
                'customers.name as customer_name',
                'customers.phone',
                'complain_statuses.name as status_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'DATE',
            'DESCRIPTION',
            'CODE',
            'NAME',
            'CUSTOMER PHONE',
            'COMPLAIN STATUS',
        ];
    }

    public function csvHeadings(): array
    {
        return [
            'date',
            'description',
            'code',
            'customer_name',
            'phone',
            'status_name',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:F1'; // All headers
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
            },
        ];
    }
}
