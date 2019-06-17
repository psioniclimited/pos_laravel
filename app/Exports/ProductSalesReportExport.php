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

class ProductSalesReportExport implements FromCollection, WithHeadings, WithEvents
{
    protected $filter;
    protected $request;
    function __construct(Request $request)
    {
//        $this->filter = new OrderFilter($request);
        $this->request = $request;
    }

    public function collection() {
        $productWithOptionsQuery = "SELECT 
                  options.id,
                  products.name, 
                  options.type, 
                  SUM(quantity) as quantity, 
                  SUM(order_details.total) as total, 
                  (CASE WHEN products.has_options = 1 THEN options.price ELSE products.sale_price END) AS sale_price 
                  FROM options
                  JOIN order_details ON order_details.option_id = options.id
                  JOIN orders on order_details.order_id = orders.id
                  JOIN products ON products.id = options.product_id";

        $productWithoutOptionsQuery = "SELECT 
                  products.id, 
                  products.name,
                  '' as type, 
                  SUM(quantity) as quantity, 
                  SUM(order_details.total) as total,  
                  products.sale_price AS sale_price 
                  FROM products
                  JOIN order_details ON order_details.product_id = products.id
                  JOIN orders on order_details.order_id = orders.id
                  WHERE products.has_options = 0";

        if ($this->request->date) {
            $dateArray = explode(',',$this->request->date);
            $dateArray[0] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[0])->format('Y-m-d');
            if (sizeof($dateArray) > 1 && !empty($dateArray[1])) {
                $dateArray[1] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[1])->format('Y-m-d');
                $productWithOptionsQuery = $productWithOptionsQuery . " WHERE orders.date BETWEEN '$dateArray[0]' AND '$dateArray[1] 23:59:59'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND orders.date BETWEEN '$dateArray[0]' AND '$dateArray[1] 23:59:59'";

            } else {
                $productWithOptionsQuery = $productWithOptionsQuery . " WHERE orders.date BETWEEN '$dateArray[0]' AND '$dateArray[0] 23:59:59'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND orders.date BETWEEN '$dateArray[0]' AND '$dateArray[0] 23:59:59'";
            }
            if ($this->request->global) {
                $productWithOptionsQuery = $productWithOptionsQuery . " AND products.name LIKE '%" . $this->request->global . "%'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND products.name LIKE '%" . $this->request->global . "%'";
            }
        } else {
            if ($this->request->global) {
                $productWithOptionsQuery = $productWithOptionsQuery . " WHERE products.name LIKE '%" . $this->request->global . "%'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND products.name LIKE '%" . $this->request->global . "%'";
            }
        }

        $productWithOptionsQuery = $productWithOptionsQuery . " GROUP BY options.id";
//        GROUP BY products.id
        $productWithoutOptionsQuery = $productWithoutOptionsQuery . " GROUP BY products.id";
        $query = $productWithOptionsQuery . " UNION " . $productWithoutOptionsQuery;
        $sales_report = DB::select($query);
//        dd($sales_report);

        return collect($sales_report);
    }

    public function headings(): array
    {
        return [
            'PRODUCT NAME',
            'OPTION TYPE',
            'QUANTITY',
            'UNIT PRICE',
            'TOTAL',
        ];
    }

    public function csvHeadings(): array
    {
        return [
            'name' => 'PRODUCT NAME',
            'type' => 'OPTION TYPE',
            'quantity' => 'QUANTITY',
            'sale_price' => 'UNIT PRICE',
            'total' => 'TOTAL',
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
