<?php

namespace App\Http\Controllers\api;

use App\Branch;
use PHPUnit\Exception;
use Illuminate\Http\Request;
use App\Traits\GeneralScopes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BranchController extends Controller
{       use GeneralScopes;

    public function createBranch(Request $request){
 

    $branch_record = $this->validate($request, [
        "name" => "required",
        "address" => "required",
        "manager" => "required",
        "number" => "required",
    ]);
    
    try{
    $this->getAuthenticatedUser();
        //get Authenticated user detail
        Auth::user()->id;

        $branch_record['user_id']=Auth::user()->id;
// return $branch_record;
        Branch::create($branch_record);

        $success['status'] = true ;

        $success['message'] = "Branch Created";
        return $success;
    }catch(Exception $e)
        {
         throw ValidationException::withMessages(['invalid_token' => $e->getMessage()]);
         }
    
    
    }
    public function getBranch(){
        try{
            $this->getAuthenticatedUser();
                //get Authenticated user detail
              return $data = Branch::where('user_id' ,  Auth::user()->id)->get();
                $response['status'] = true ;
                $response['data'] = $data;
                return $response;
            }catch(Exception $e)
                {
                 throw ValidationException::withMessages(['invalid_token' => $e->getMessage()]);
                 }
            
        
    }
    public function getEditBranch($id){
        try{
            $data = Branch::where('id',$id)->first();
            $response['status'] = true;
            $response['message'] = 'Record Recieved';
            $response['data'] = $data;
        }catch(Exception $e){
            $response['status'] = false;
            $response['message'] = $e->getMessage();
        }
        return json_encode($response) ;
    }

    public function updateBranch (Request $request ,$id){
        

    $branch_record = $this->validate($request, [
        "name" => "required",
        "address" => "required",
        "manager" => "required",
        "number" => "required",
    ]);
    
    try{
        
        Branch::where('id' , $id)->update($branch_record);

        $success['status'] = true ;

        $success['message'] = "Branch Updated";
        return $success;
    }catch(Exception $e)
        {
         throw ValidationException::withMessages(['invalid_token' => $e->getMessage()]);
         }
    
    
    }
    public function deleteBranch($id){

        // return $id;
       try{ Branch::where('id',$id)->delete();
        $response['status'] = true ;
        $response['message'] = "Branch Deleted";
    }catch(Exception $e){
        $response['status'] = false ;
        $response['message'] = $e->getMessage();
    }
    return $response;
}
}