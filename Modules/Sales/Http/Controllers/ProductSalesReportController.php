<?php

namespace Modules\Sales\Http\Controllers;

use App\Filters\OrderDetailFilter;
use App\Filters\ProductFilter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Option;
use Modules\Sales\Entities\OrderDetail;
use DB;
use Modules\Sales\Entities\Product;

class ProductSalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param ProductFilter $filter
     * @return Response
     */
    public function index(Request $request, ProductFilter $filter)
    {
        $query = "SELECT 
                  options.id,
                  products.name, 
                  options.type, 
                  SUM(quantity) as quantity, 
                  SUM(order_details.total) as total, 
                  (CASE WHEN products.has_options = 1 THEN options.price ELSE products.sale_price END) AS sale_price 
                  from options
                  JOIN order_details ON order_details.option_id = options.id
                  JOIN products ON products.id = options.product_id
                  GROUP BY options.id
                  UNION
                  SELECT 
                  products.id, 
                  products.name, '' as type, 
                  SUM(quantity) as quantity, 
                  SUM(order_details.total) as total,  
                  products.sale_price AS sale_price from products
                  JOIN order_details ON order_details.product_id = products.id
                  WHERE products.has_options = 0
                  GROUP BY products.id";
        $sales_report = DB::select($query);
        return response()->json($sales_report);

        //        $sales_report = Product::filter($filter)
//            ->leftJoin('options', 'products.id', 'options.product_id')
//            ->leftJoin('order_details', 'order_details.product_id', 'products.id')
//            ->select(
//                'products.id',
//                'products.name',
//                'options.type',
////                DB::raw('SUM(order_details.quantity) as quantity'),
//                DB::raw('(CASE WHEN order_details.option_id = options.id AND order_details.product_id = products.id THEN SUM(order_details.quantity) ELSE products.sale_price END) AS quantity'),
//                DB::raw('SUM(order_details.total) as total'),
//                DB::raw('(CASE WHEN products.has_options = 1 THEN options.price ELSE products.sale_price END) AS sale_price')
//            )
//            ->groupBy('options.id', 'products.id')
//            ->paginate($request->per_page);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('sales::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('sales::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('sales::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
