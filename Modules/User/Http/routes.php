<?php

Route::group(['middleware' => 'api', 'prefix' => 'user', 'namespace' => 'Modules\User\Http\Controllers'], function () {
    //Users
    Route::get('/', 'UserController@index')->middleware(['set.tenant', 'jwt.auth', 'permission:user.index']);
    Route::post('/', 'UserController@store')->middleware(['set.tenant', 'jwt.auth', 'permission:user.store', 'plan']);

    Route::post('/login', 'AuthenticationController@login');
    Route::post('/logout', 'AuthenticationController@logout');

    Route::get('/profile', 'ProfileController@index')->middleware(['set.tenant', 'jwt.auth']);

    //PermissionController
    Route::get('/permission', 'PermissionController@index')->middleware(['jwt.auth']);
    Route::get('/permission/{permission}', 'PermissionController@show')->middleware(['jwt.auth']);
    Route::post('/permission', 'PermissionController@store')->middleware(['jwt.auth']);
    Route::put('/permission/{permission}', 'PermissionController@update')->middleware(['jwt.auth']);
    Route::delete('/permission/{permission}', 'PermissionController@destroy')->middleware(['jwt.auth']);

    //RoleController
    Route::get('/role', 'RoleController@index')->middleware(['set.tenant', 'jwt.auth', 'permission:role.index']);
    Route::get('/role/{role}', 'RoleController@show')->middleware(['set.tenant', 'jwt.auth', 'permission:role.show']);
    Route::post('/role', 'RoleController@store')->middleware(['set.tenant', 'jwt.auth', 'permission:role.store']);
    Route::put('/role/{role}', 'RoleController@update')->middleware(['set.tenant', 'jwt.auth', 'permission:role.update']);

    //RolePermissionController
    Route::get('/role/{role}/permission', 'RolePermissionController@index')->middleware(['set.tenant', 'jwt.auth', 'permission:role_permission.index']);
    Route::post('/role/{role}/permission', 'RolePermissionController@store')->middleware(['set.tenant', 'jwt.auth', 'permission:role_permission.store']);
    Route::put('/role/{role}/permission/{permission}', 'RolePermissionController@update')->middleware(['set.tenant', 'jwt.auth', 'permission:role_permission.update']);

    Route::get('/{id}', 'UserController@show')->middleware(['set.tenant', 'jwt.auth', 'permission:user.show']);
    Route::put('/{user}', 'UserController@update')->middleware(['set.tenant', 'jwt.auth', 'permission:user.update']);
});

Route::group(['middleware' => 'api', 'prefix' => '', 'namespace' => 'Modules\User\Http\Controllers'], function () {
    Route::get('report/download', 'ReportController@index')->middleware(['set.tenant', 'jwt.auth', 'permission:report.index']); // add token in frontend

    //SignUp
    Route::post('/signup', 'SignUpController@store');
});
