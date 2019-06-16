<?php

namespace Modules\Sales\Http\Controllers;

use App\Filters\OrderDetailFilter;
use App\Filters\ProductFilter;
use Carbon\Carbon;
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
//        $queryDate = Carbon::createFromFormat('D M d Y H:i:s e+', $request->date)->format('Y-m-d');
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

        if ($request->date) {
            $dateArray = explode(',', $request->date);
            $dateArray[0] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[0])->format('Y-m-d');
            if (sizeof($dateArray) > 1 && !empty($dateArray[1])) {
                $dateArray[1] = Carbon::createFromFormat('D M d Y H:i:s e+', $dateArray[1])->format('Y-m-d');
                $productWithOptionsQuery = $productWithOptionsQuery . " WHERE orders.date BETWEEN '$dateArray[0]' AND '$dateArray[1] 23:59:59'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND orders.date BETWEEN '$dateArray[0]' AND '$dateArray[1] 23:59:59'";

            } else {
                $productWithOptionsQuery = $productWithOptionsQuery . " WHERE orders.date BETWEEN '$dateArray[0]' AND '$dateArray[0] 23:59:59'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND orders.date BETWEEN '$dateArray[0]' AND '$dateArray[0] 23:59:59'";
            }
            if ($request->global) {
                $productWithOptionsQuery = $productWithOptionsQuery . " AND products.name LIKE '%" . $request->global . "%'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND products.name LIKE '%" . $request->global . "%'";
            }
        } else {
            if ($request->global) {
                $productWithOptionsQuery = $productWithOptionsQuery . " WHERE products.name LIKE '%" . $request->global . "%'";
                $productWithoutOptionsQuery = $productWithoutOptionsQuery . " AND products.name LIKE '%" . $request->global . "%'";
            }
        }

        $productWithOptionsQuery = $productWithOptionsQuery . " GROUP BY options.id";
//        GROUP BY products.id
        $productWithoutOptionsQuery = $productWithoutOptionsQuery . " GROUP BY products.id";
        $query = $productWithOptionsQuery . " UNION " . $productWithoutOptionsQuery;
//        $query = "SELECT
//                  options.id,
//                  products.name,
//                  options.type,
//                  SUM(quantity) as quantity,
//                  SUM(order_details.total) as total,
//                  (CASE WHEN products.has_options = 1 THEN options.price ELSE products.sale_price END) AS sale_price
//                  from options
//                  JOIN order_details ON order_details.option_id = options.id
//                  JOIN products ON products.id = options.product_id
//                  GROUP BY options.id
//                  UNION
//                  SELECT
//                  products.id,
//                  products.name,
//                  '' as type,
//                  SUM(quantity) as quantity,
//                  SUM(order_details.total) as total,
//                  products.sale_price AS sale_price
//                  from products
//                  JOIN order_details ON order_details.product_id = products.id
//                  WHERE products.has_options = 0
//                  GROUP BY products.id";
        $sales_report = DB::select($query);
        return response()->json($sales_report);
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
