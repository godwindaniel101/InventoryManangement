<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_quantity',
        'product_cost',
        'product_id',
        'product_name',
        'product_production_date',
        'product_expiry_datae',
        'product_discount',
        'product_unitTotal',
        'unitGross',
     ];
     public function product()
     {
         return $this->hasOne('App\Product', 'id' ,'product_id');
     }
}
