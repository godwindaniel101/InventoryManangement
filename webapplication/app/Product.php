<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'product_name' ,
        'product_cost' ,
        'product_supplier' ,
        'product_price' ,
    ];

}
