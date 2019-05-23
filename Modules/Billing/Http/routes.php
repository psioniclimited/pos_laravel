<?php

Route::group(['middleware' => 'api', 'prefix' => '', 'namespace' => 'Modules\Billing\Http\Controllers'], function () {
    Route::get('/', 'BillingController@index');

    Route::get('/customer', 'CustomerController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.index']);
    Route::post('/customer', 'CustomerController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.store', 'plan']);
    Route::get('/customer/{id}', 'CustomerController@show')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.show']);
    Route::put('/customer/{customer}', 'CustomerController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.update']);
    Route::delete('/customer/{customer}', 'CustomerController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.delete']);

    Route::get('/internet_customer', 'InternetCustomerController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.index']);
    Route::post('/internet_customer', 'InternetCustomerController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.store', 'plan']);
    Route::get('/internet_customer/{id}', 'InternetCustomerController@show')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.show']);
    Route::put('/internet_customer/{customer}', 'InternetCustomerController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.update']);
    Route::delete('/internet_customer/{customer}', 'InternetCustomerController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.delete']);

    Route::get('/generate_code', 'GenerateCodeController@index')->middleware(['jwt.auth', 'set.tenant']);

    Route::get('/customer_due', 'CustomerDueListController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.index']);
    Route::get('/internet_customer_due', 'InternetCustomerDueListController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:customer.index']);

    Route::get('/subscription_type', 'SubscriptionTypeController@index')->middleware(['jwt.auth']);
    Route::get('/subscription_type/{subscription_type}', 'SubscriptionTypeController@show')->middleware(['jwt.auth']);
    Route::post('/subscription_type', 'SubscriptionTypeController@store')->middleware(['jwt.auth']);
    Route::put('/subscription_type/{subscription_type}', 'SubscriptionTypeController@update')->middleware(['jwt.auth']);

    Route::get('/area', 'AreaController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:area.index']);
    Route::get('/area/{area}', 'AreaController@show')->middleware(['jwt.auth', 'set.tenant', 'permission:area.show']);
    Route::post('/area', 'AreaController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:area.store']);
    Route::put('/area/{area}', 'AreaController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:area.update']);
    Route::delete('/area/{area}', 'AreaController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:area.delete']);

    Route::get('/customer/{customer_id}/status', 'CustomerStatusController@index')->middleware(['jwt.auth']); // can be accessed by everyone
    Route::post('/customer/{customer_id}/status', 'CustomerStatusController@store')->middleware(['jwt.auth']);
    Route::put('/customer/customer_status/{customer_status}', 'CustomerStatusController@update')->middleware(['jwt.auth']);

    Route::get('/bill_collection', 'BillCollectionController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:bill_collection.index']);
    Route::post('/bill_collection', 'BillCollectionController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:bill_collection.store']);

    Route::post('/bill_collection/{bill_collection_id}/discount', 'DiscountController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:discount.store']);

    Route::get('/refund_history', 'RefundController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:refund.index']);
    Route::post('/bill_collection/{bill_collection}/refund', 'RefundController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:refund.store']);

    Route::get('/complain', 'ComplainController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:complain.index']);
    Route::post('/complain', 'ComplainController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:complain.store']);
    Route::get('/complain/{id}', 'ComplainController@show')->middleware(['jwt.auth', 'set.tenant', 'permission:complain.show']);
    Route::put('/complain/{complain}', 'ComplainController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:complain.update']);
    Route::delete('/complain/{complain}', 'ComplainController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:complain.delete']);

    Route::put('/complain_update/{complain}', 'ComplainUpdateStatusController@update')->middleware(['jwt.auth', 'set.tenant']);

    Route::get('/complain_status', 'ComplainStatusController@index')->middleware(['jwt.auth']);

    Route::get('/dashboard', 'DashboardController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:dashboard.index']);
    Route::get('/collector_ranking', 'CollectorRankingController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:dashboard.index']);
    Route::get('/connections', 'ConnectedCustomerController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:dashboard.index']);

    Route::get('/mail', 'DashboardController@mail');

    Route::get('/fee_type', 'FeeTypeController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_type.index']);
    Route::post('/fee_type', 'FeeTypeController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_type.store']);
    Route::get('/fee_type/{fee_type}', 'FeeTypeController@show')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_type.show']);
    Route::put('/fee_type/{fee_type}', 'FeeTypeController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_type.update']);
    Route::delete('/fee_type/{fee_type}', 'FeeTypeController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_type.delete']);

    Route::get('/fee_collection', 'FeeCollectionController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_collection.index']);
    Route::post('/fee_collection', 'FeeCollectionController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:fee_collection.store']);

    Route::post('/fee_collection/{fee_collection}/refund', 'RefundFeeController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:refund_fee_collection.store']);

    Route::get('/total_due', 'TotalController@due')->middleware(['jwt.auth', 'set.tenant']);
    Route::get('/total_bill', 'TotalController@bill_collected')->middleware(['jwt.auth', 'set.tenant']);
    Route::get('/total_fee', 'TotalController@fee_collected')->middleware(['jwt.auth', 'set.tenant']);

//    Route::put('/customer/customer_status/{customer_status}', 'BillCollectionController@update');

    //mobile api customerController


});
