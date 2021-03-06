<?php

Route::group(['middleware' => 'api', 'prefix' => '', 'namespace' => 'Modules\Sales\Http\Controllers'], function()
{
    Route::get('/', 'SalesController@index');

    Route::get('/client','ClientController@index')->middleware(['jwt.auth', 'set.tenant']);
    Route::post('/client', 'ClientController@store')->middleware(['jwt.auth', 'set.tenant']);
    Route::get('/client/{id}','ClientController@show')->middleware(['jwt.auth', 'set.tenant']);

    // Category Controller
    Route::get('/category','CategoryController@index')->middleware(['jwt.auth', 'set.tenant']);

    // Product Controller
    Route::get('/product','ProductController@index')->middleware(['jwt.auth', 'set.tenant']);

    //address controller
    Route::get('/address','AddressController@index')->middleware(['jwt.auth', 'set.tenant']);

    // Order Controller
    Route::get('/order','OrderController@index')->middleware(['jwt.auth', 'set.tenant']);
    Route::get('/order/{id}','OrderController@show')->middleware(['jwt.auth', 'set.tenant']);

    // Product Sales Report Controller
    Route::get('/product_sales_report','ProductSalesReportController@index')->middleware(['jwt.auth', 'set.tenant']);

    // Total Sales Controller
    Route::get('/total_paid', 'TotalSalesController@total_paid')->middleware(['jwt.auth', 'set.tenant']);
    // Total sales discount
    Route::get('/total_discount', 'TotalSalesController@total_discount')->middleware(['jwt.auth', 'set.tenant']);
    // grand total
    Route::get('/grand_total', 'TotalSalesController@grand_total')->middleware(['jwt.auth', 'set.tenant']);
});
