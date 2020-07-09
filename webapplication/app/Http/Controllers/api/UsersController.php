<?php

namespace App\Http\Controllers\API;

use App\User;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Null_;

class UsersController extends Controller
{ 
    
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::allows('isAdmin') || Gate::allows('isEmployee')) {

         return  User::latest()->paginate(5);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  $this->validate($request, [
        "name" => "required|min:5|max:191|string",
        "email" => "required|max:191|email|string|unique:users",
        "position" => "required",
        "password" => "required|min:7|string",
        "type" => "required",
    ]);
         User::create([
        'name' => $request->input('name'), 
        'email' => $request->input('email'), 
        'password' => Hash::make($request->input('password')),   
        'position' => $request->input('position'),  
        'bio' => $request->input('bio'), 
        'type' => $request->input('type'), 
        'photo' => 'profile.png',
        ]);
       return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       return User::where('id',$id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id, 
            'password' => 'sometimes|min:8',  
            'position' => 'required|string', 
            'bio' => 'sometimes|string',
            'type' => 'required|string',
        ]);
        if($request->input('password') !== null){
        $request->merge(['password' => Hash::make($request->input('password'))]);}
        $user->update($request->all());
        return ['true' => "all"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  $this->authorize('isAdmin');
         User::where('id' , $id)->delete();
        return['status'=>true];
    }

    public function profile(){
        return auth('api')->user();
    }
    public function updateProfile(Request $request){
        
        $user = auth('api')->user();
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id, 
            'bio' => 'sometimes|string',
        ]);
      
        $user->update($request->all());
        return ['succes'=> 'Info Updated'];
    }

    //profile picture update

    public function uploadPhoto(Request $request){
        $user = auth('api')->user();
        $currentPhoto  = $user->photo;
        if($request->photo != $currentPhoto){
            $imageLocated = public_path('/image/profile/').$currentPhoto;
            if(file_exists($imageLocated)){
                @unlink($imageLocated);
            };
            $name = time().'.' . explode('/', explode(':', substr(
                $request->photo, 0, strpos($request->photo, ';')))[1])[1];
            \Image::make($request->photo)->save(public_path('image/profile/').$name);
                $request->merge(['photo' => $name]);
       
        }

        $user->update(['photo' => $name]);
    }

    public function changePassword(Request $request){
      $user = auth('api')->user();

        $this->validate($request, [
            'old_password' => 'required|',
            'new_password' => 'required|min:8', 
            'confirm_password' => 'required|same:new_password'
        ]);
        if (!Hash::check($request->old_password, $user->password)) {
                $validator = Validator::make([], []); // Empty data and rules fields
                $validator->errors()->add('old_password', 'Incorrect old password');
                throw new ValidationException($validator);
        }else{
            $user->update(['password' => Hash::make($request->input('new_password'))]);
        }
    }
    public function passwordCheck(Request $request){
        $user = auth('api')->user();
          if (!Hash::check($request->old_password, $user->password)) {
                  $validator = Validator::make([], []); // Empty data and rules fields
                  $validator->errors()->add('old_password', 'Incorrect old password');
                  throw new ValidationException($validator);
          }else{
              return ['message' => 'valid passwod'];
          }
      }
    public function findUser(){
        $search = \Request::get('q');
        if($search !== null){
        $users = User::where(function($query) use ($search){
            $query->where('name' , 'LIKE' , "%$search%")
            ->orWhere('email', 'LIKE' , "%$search%")
            ->orWhere('type', 'LIKE' , "%$search%");
        })->paginate(5);
    }else{
        $users = User::latest()->paginate(5);
    }
    return $users;
    }
}
