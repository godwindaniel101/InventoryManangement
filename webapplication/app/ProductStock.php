<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = [
            "user_id",
            "branch_id" ,
            "product_id" ,
            "product_count" ,
            "product_warn" ,
            "product_max" ,
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }
}
