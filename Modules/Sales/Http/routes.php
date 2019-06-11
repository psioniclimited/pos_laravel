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

    // Total Sales Controller
    Route::get('/total_paid', 'TotalSalesController@total_paid')->middleware(['jwt.auth', 'set.tenant']);

});
