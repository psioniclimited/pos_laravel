<?php

namespace App\Exports;

use App\Filters\OrderFilter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Sales\Entities\Order;
use DB;

class OrderReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filter;
    function __construct(Request $request)
    {
        $this->filter = new OrderFilter($request);
    }

    public function collection() {
        return $orders =  Order::filter($this->filter)
            ->join('clients', 'clients.id', 'orders.client_id')
            ->select(
                'orders.id',
                'orders.date',
                'clients.name',
                'orders.total',
                'orders.discount',
                DB::raw('(orders.total - (orders.total * orders.discount)/100) as grand_total')
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'DATE',
            'NAME',
            'TOTAL',
            'DISCOUNT',
            'PAID',
        ];
    }

    public function csvHeadings(): array
    {
        return [
            'date' => 'DATE',
            'name' => 'NAME',
            'total' => 'TOTAL',
            'discount' => 'DISCOUNT',
            'grand_total' => 'PAID',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('e')->setWidth(30);
            },
        ];
    }
}
