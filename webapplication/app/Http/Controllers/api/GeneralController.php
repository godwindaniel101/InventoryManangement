<?php

namespace App\Http\Controllers\api;

use App\Sales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function getTransactionId($type){
        
        $lastId = Sales::orderBy('id','DESC')->first();
        if($lastId){
            $lastId = $lastId->id + 1;
        }else{
            $lastId = 1;
        }
        $Id = str_pad($lastId,8,"0",STR_PAD_LEFT);
        if($type == 'sales'){
            $abr = 'SL';
        }else if($type == 'purchase'){
            $abr = 'PR';
        }else{
            $abr = 'NO';
        }
        $transactionId = $abr . $Id;
        return $transactionId;
    }
 
}
