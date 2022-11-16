<?php

namespace Irpcpro\TableSoft\Testing\Http\Controllers;

use App\Http\Controllers\Controller;
use Irpcpro\TableSoft\Testing\Models\Product;

class TableSoftControllers extends Controller
{

    public function importProduct()
    {
        $getData = productData();
        foreach ($getData as $item) {
            Product::create([
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'rating' => $item->rating,
                'stock' => $item->stock,
                'brand' => $item->brand,
                'thumbnail' => $item->thumbnail,
            ]);
        }
        echo 'ok';
    }

}
