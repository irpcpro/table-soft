<?php

namespace Irpcpro\TableSoft\Testing\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Irpcpro\TableSoft\Testing\Http\Controllers\TableSoftControllers as Controller;
use Irpcpro\TableSoft\Testing\Models\Product;
use TableSoft;

class TableSoftTestControllers extends Controller
{

    public function index()
    {

//        $data1 = productData();

//        $data1 = Product::query();


        $data1 = Http::get('https://dummyjson.com/products');
        $data1 = collect($data1->json()['products']);

        $table = TableSoft::data($data1);
        $table = $table->column('Title', 'title:string', 'sort')->searchable();
        $table = $table->column('Image', 'thumbnail:string', function($value){
            return "<img width='70px' height='20px' src='$value?ver=1'/>";
        });
        $table = $table->column('Description', 'description:string', 'sort:asc')->searchable();
        $table = $table->column('Price', 'price:int', 'sort', function($value){
            return $value . '$';
        })->setWidth(50, 'px')->searchable();
        $table = $table->rowCounter('row')->setWidth(20,'px');
        $table = $table->setCaching('table-product4');
        $table = $table->paginate(10);
        $data = $table->get();

        return view('tableSoft::table-soft-test', compact('data'));
    }

}
