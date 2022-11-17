<?php

Route::group(['namespace' => 'Irpcpro\TableSoft\Testing\Http\Controllers', 'middleware' => 'web'],function(){
    // generate product data
    Route::get('/import-data', 'TableSoftControllers@importData');

    // for test
    Route::get('/table-soft-test', 'TableSoftTestControllers@index');


    // login
    Route::middleware('guest')->get('/login', 'LoginController@login')->name('tableview.login');
    Route::post('/login', 'LoginController@loginPost')->name('tableview.login.post');
    Route::post('/logout', 'LoginController@logout')->name('tableview.logout');

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/home', 'DashboardController@home')->name('tableview.home');
        Route::get('/table-setting', 'DashboardController@tableSetting')->name('tableview.tableSetting');
    });

});
