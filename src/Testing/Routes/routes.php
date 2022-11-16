<?php

Route::group(['namespace' => 'Irpcpro\TableSoft\Testing\Http\Controllers'],function(){
    // generate product data
    Route::get('/import-product', 'TableSoftControllers@importProduct');

    // for test
    Route::get('/table-soft-test', 'TableSoftTestControllers@index');

});
