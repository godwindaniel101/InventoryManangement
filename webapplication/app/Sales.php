<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
 protected $fillable = [
    'user_id',
    'branch_id',
    'transaction_id',
    'product_quantity',
    'product_cost',
    'product_id',
    'product_stock_id',
    'product_name',
    'product_discount',
    'product_unitTotal',
    'unitGross',
    
 ];
 public function branch()
 {
     return $this->belongsTo('App\Branch','branch_id', 'id' );
 }
 public function product()
 {
     return $this->belongsTo('App\Product', 'product_id' ,'id');
 }
 public function productstock()
 {
     return $this->belongsTo('App\ProductStock', 'product_stock_id' ,'id');
 }
}
