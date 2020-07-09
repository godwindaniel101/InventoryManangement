<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
 protected $fillable = [
    'transaction_id',
    'product_quantity',
    'product_price',
    'product_id',
    'product_name',
    'product_discount',
    'product_unitTotal',
    'unitGross',
 ];
 public function product()
 {
     return $this->hasOne('App\Product', 'id' ,'product_id');
 }
}
