<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
protected $fillable = [
        'transaction_id',
        'branch',
        'total',
        'transaction_type',
        'gross',
     ];
  
}
