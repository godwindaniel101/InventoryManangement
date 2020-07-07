<?php

use App\Http\Controllers\UserApiController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::resource('employee','EmployeeController');
Route::get('employeeApi','EmployeeController@create');

Route::get('employeeApi','UserApiController@employeeApi');

Route::post('register' , 'UserApiController@register');
Route::post('login' , 'UserApiController@login');
Route::get('checkuser' , 'UserApiController@getAuthenticatedUser');
Route::post('checkuser' , 'UserApiController@getAuthenticatedUser');