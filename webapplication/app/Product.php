<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'product_name' ,
        'product_cost' ,
        'product_supplier' ,
        'product_price' ,
    ];
//     public function sales()
//  {
//      return $this->hasMany('App\Sales');
//  }
}
