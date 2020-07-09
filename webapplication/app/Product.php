<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name' ,
        'product_cost' ,
        'product_price' ,
        'product_count' ,
        'product_supplier' ,
        'product_max' ,
        'product_warn' ,
    ];

}
