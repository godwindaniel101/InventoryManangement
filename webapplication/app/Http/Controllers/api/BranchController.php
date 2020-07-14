<?php

namespace App\Http\Controllers\api;

use App\Branch;
use App\UserSetting;
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
        $branch_record['user_id']=$this->getAuthenticatedUser()->id;
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
        // try{
            
                //get Authenticated user detail
              return $data = Branch::where('user_id' , $this->getAuthenticatedUser()->id)->paginate(1);
            //     $response['status'] = true ;
            //     $response['data'] = $data;
            //     return $response;
            // }catch(Exception $e)
            //     {
            //      throw ValidationException::withMessages(['invalid_token' => $e->getMessage()]);
            //      }
            
        
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
    public function getUserCurrentBranch(){
        ;
        $user_id = $this->getAuthenticatedUser()->id;

        $user_default = UserSetting::where('user_id' , $user_id)->first();
        // return $user_default;
        if($user_default){
        $user_record['branch_id'] = $user_default->branch ? $user_default->branch->id : 0;
        $user_record['branch_name'] = $user_default->branch ? $user_default->branch->name : 'Select Branch';
       
        }else{
        $user_record['branch_id'] = 'Select';
        $user_record['branch_name'] = 'Select';
        }
        return $user_record;
       
    }
    public function updateDefaultBranch($id){
     
        $user_id = $this->getAuthenticatedUser()->id;
        $user = UserSetting::where('user_id' , $user_id);
        $user_default = $user->first();
        // return $user_default;
        if($user_default === null ){
            return UserSetting::create([
                'user_id' => $user_id,
                'branch_id' =>$id,
            ]);
        }else{
            return  $user->update([
                'branch_id' => $id
            ]);
        }
    }
}