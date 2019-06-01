<?php

Route::group(['middleware' => 'api', 'prefix' => 'mobileapi', 'namespace' => 'Modules\MobileAPI\Http\Controllers'], function()
{
    Route::get('/customers', 'MobileCustomerController@index')->middleware(['jwt.auth', 'set.tenant']);
    Route::post('/bill_collection', 'MobileBillCollectionController@store')->middleware(['jwt.auth', 'set.tenant']);
    Route::post('/login', 'MobileLoginController@login');
    Route::post('/order','MobileOrderController@store')->middleware(['jwt.auth', 'set.tenant']);
});
