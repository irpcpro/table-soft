<?php

namespace Irpcpro\TableSoft\Testing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'rating',
        'stock',
        'brand',
        'thumbnail',
    ];

}
