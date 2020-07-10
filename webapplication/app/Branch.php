<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'address',
        'id',
        'manager',
        'name',
        'number',
        'user_id',
    ];
}
