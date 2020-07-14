<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesRecord extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'branch_id',
        'net_total',
        'total_quantity',
        'status',
     ];
    public function salesdata()
 {
     return $this->hasMany('App\Sales' , 'transaction_id' , 'transaction_id');
 }
 public function branch()
 {
     return $this->belongsTo('App\Branch');
 }
 public function product()
 {
     return $this->belongsTo('App\Product');
 }
 public function user()
 {
     return $this->belongsTo('App\User');
 }
}
