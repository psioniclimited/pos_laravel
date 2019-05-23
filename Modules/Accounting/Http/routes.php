<?php

Route::group(['middleware' => 'api', 'prefix' => '', 'namespace' => 'Modules\Accounting\Http\Controllers'], function()
{
    Route::get('/chart_of_account', 'ChartOfAccountController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:chart_of_account.index']);
    Route::post('/chart_of_account/{parent_id}', 'ChartOfAccountController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:chart_of_account.store']);
    Route::put('/chart_of_account/{chart_of_account}', 'ChartofAccountController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:chart_of_account.update']);
    Route::delete('/chart_of_account/{chart_of_account}', 'ChartOfAccountController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:chart_of_account.delete']);

    Route::get('/expense', 'ExpenseController@index')->middleware(['jwt.auth', 'set.tenant', 'permission:expense.index']);
    Route::get('/expense/{id}', 'ExpenseController@show')->middleware(['jwt.auth', 'set.tenant', 'permission:expense.show']);
    Route::post('/expense', 'ExpenseController@store')->middleware(['jwt.auth', 'set.tenant', 'permission:expense.store']);
    Route::put('/expense/{id}', 'ExpenseController@update')->middleware(['jwt.auth', 'set.tenant', 'permission:expense.update']);
    Route::delete('/expense/{expense}', 'ExpenseController@destroy')->middleware(['jwt.auth', 'set.tenant', 'permission:expense.delete']);

});
