<?php

namespace App\Exports;

use App\Filters\ExpenseFilter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Accounting\Entities\Expense;

class ExpenseReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filter;
    function __construct(Request $request)
    {
        $this->filter = new ExpenseFilter($request);
    }

    public function collection() {
        return $expenses = Expense::filter($this->filter)
            ->with('paid_with')
            ->with('expense_details.chart_of_account')
            ->join('expense_details', 'expenses.id', 'expense_details.expense_id')
            ->join('chart_of_accounts', 'expense_details.chart_of_account_id', 'chart_of_accounts.id')
            ->join('chart_of_accounts as coa', 'expenses.paid_with_id', 'coa.id')
            ->select(
                'expenses.date',
                'expenses.description',
                'chart_of_accounts.name as expense_type',
                'expenses.amount'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'DATE',
            'DESCRIPTION',
            'EXPENSE TYPE',
            'AMOUNT',
        ];
    }

    public function csvHeadings(): array
    {
        return [
            'date',
            'description',
            'expense_type',
            'amount',
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
