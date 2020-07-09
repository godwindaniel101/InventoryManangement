<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'sender_id', 'sender_status', 'reciever_id',  'reciever_status', 'message'
    ];
}
