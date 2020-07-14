<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'branch_id',
        'user_id',
        'profile_pic',
        'user_setting_status',
        'user_color',
    ];

    public function branch()
 {
     return $this->belongsTo('App\Branch');
 }
}
