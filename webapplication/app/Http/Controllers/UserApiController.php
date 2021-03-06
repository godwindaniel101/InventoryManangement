<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use App\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserApiController extends Controller
{
        public function employeeApi(){
                return ['RECORD ' => 'YESSS'];
        }
    public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Invalid Details'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            return response()->json(compact('token'));
        }

        public function register(Request $request)
        {
                // return $request;
                $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'email'=>'required|unique:users|email:rfc,dns',
                    'password' => 'required|min:8',
                    'confirm_password' =>'same:password'
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);
                UserSetting::create([
                        'user_id' => $user->id,
                        'branch_id' => 0,
                        'profile_pic' => 'profile.png',
                        'user_color'=>'white',
                        'user_setting_status' => 'update'
            ]);

            $token = JWTAuth::fromUser($user);
            $success = true;
            return response()->json(compact('user','token','success'),201);
          
        }

        public function getAuthenticatedUser()
            {
                    try {

                            if (! $user = JWTAuth::parseToken()->authenticate()) {
                                    return response()->json(['user_not_found'], 404);
                            }

                    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                            return response()->json(['token_expired'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                            return response()->json(['token_invalid'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                            return response()->json(['token_absent'], $e->getStatusCode());

                    }

                    return response()->json(compact('user'));
            }
}
