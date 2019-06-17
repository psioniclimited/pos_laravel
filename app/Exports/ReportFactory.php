<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Laracsv\Export;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportFactory
{
    private $extension;

//    public function view(): View
//    {
//        return view('exports.invoices', [
//            'invoices' => Invoice::all()
//        ]);
//    }

    public function getReport(Request $request)
    {
//        dd('here');
        $report_name = $this->getReportExport($request);
        $this->extension = $request->report_type;
        switch ($request->report_type) {
            case 'xlsx':
                return Excel::download($report_name, $request->report_name . '.' . $this->extension, \Maatwebsite\Excel\Excel::XLSX);
                break;
            case 'pdf':
                $data = ($report_name)->collection();
//                return view('reports.' . $request->report_name, $data);
                $pdf = PDF::loadView('reports.' . $request->report_name, $data); // warning -> conflict in future
                return $pdf->download('report.pdf');
                break;
            case 'csv':
                $data = ($report_name)->collection();
                $csvExporter = new Export();
                return response($csvExporter->build($data, $report_name->csvHeadings())->getCsv());
//                $csvExporter->build($data, $report_name->csvHeadings())->download();
                break;
            default:
                return \Maatwebsite\Excel\Excel::XLSX;
        }
    }

    private function getReportExport($request)
    {
        switch ($request->report_name) {
            case 'users':
                return new UsersReportExport($request);
                break;
            case 'customers':
                return new CustomerReportExport($request);
                break;
            case 'internet_customers':
                return new InternetCustomerReportExport($request);
                break;
            case 'customers_due':
                return new CustomerDueReportExport($request);
                break;
            case 'internet_customers_due':
                return new InternetCustomerDueReportExport($request);
                break;
            case 'bill_collections':
                return new BillCollectionReportExport($request);
                break;
            case 'internet_bill_collections':
                return new InternetBillCollectionReportExport($request);
                break;
            case 'complains':
                return new ComplainsReportExport($request);
                break;
            case 'refunds':
                return new RefundsReportExport($request);
                break;
            case 'internet_refunds':
                return new InternetRefundsReportExport($request);
                break;
            case 'expenses':
                return new ExpenseReportExport($request);
                break;
            case 'monthly_report':
                return new MonthlyReportExport($request);
                break;
            case 'orders':
                return new OrderReportExport($request);
                break;
            case 'product_sales':
                return new ProductSalesReportExport($request);
                break;
            default:
                return 'test';
        }
    }
}