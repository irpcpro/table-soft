<?php

namespace Irpcpro\TableSoft\Testing\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Irpcpro\TableSoft\Testing\Models\Product;

class TableSoftControllers extends Controller
{

    public function importData()
    {
        // import products
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

        // import new user
        User::create([
            'name' => 'ali',
            'email' => 'designer.pcpro@yahoo.com',
            'password' => Hash::make('123'),
        ]);

        echo 'ok';
    }

}
