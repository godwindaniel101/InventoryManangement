<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
protected $fillable = [
        'user_id',
        'transaction_id',
        'branch_id',
        'total',
        'transaction_type',
        'gross',
     ];
     public function user()
     {
         return $this->belongsTo('App\User', 'user' ,'id');
     }
}
