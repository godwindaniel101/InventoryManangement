<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseRecord extends Model
{
    protected $fillable = [
        'transaction_id',
        'branch',
        'net_total',
        'total_quantity',
        'status',
     ];
    public function salesdata()
 {
     return $this->hasMany('App\Sales' , 'transaction_id' , 'transaction_id');
 }
}
