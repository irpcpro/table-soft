<?php

Route::group(['namespace' => 'Irpcpro\TableSoft\Testing\Http\Controllers', 'middleware' => 'web'],function(){
    // generate product data
    Route::get('/import-data', 'TableSoftControllers@importData');

    // for test
    Route::get('/table-soft-test', 'TableSoftTestControllers@index');


    // login
    Route::middleware('guest')->get('/login', 'LoginController@login')->name('tableSoft.login');
    Route::post('/login', 'LoginController@loginPost')->name('tableSoft.login.post');
    Route::post('/logout', 'LoginController@logout')->name('tableSoft.logout');

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/home', 'DashboardController@home')->name('tableSoft.home');
        Route::get('/table-setting', 'DashboardController@tableSetting')->name('tableSoft.tableSetting');
        Route::post('/table-setting/update', 'DashboardController@tableSettingUpdate')->name('tableSoft.tableSetting.update');
        Route::post('/get-row', 'DashboardController@tableSettingGetRow')->name('tableSoft.tableSetting.ajax');

        Route::get('/management-table', 'DashboardController@managementTable')->name('tableSoft.managementTable');
    });

});
