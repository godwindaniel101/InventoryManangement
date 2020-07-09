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


//strore
Route::resource('product', 'API\ProductController');

Route::resource('user', 'API\UsersController');

Route::resource('sales', 'API\MakeSalesController');
Route::post('createSales', 'API\MakeSalesController@store2');
Route::get('todaySales', 'API\MakeSalesController@todaySales');
Route::get('loadSales', 'API\MakeSalesController@loadSales');
Route::get('salesRecord', 'API\MakeSalesController@salesRecord');
Route::get('getSalesRecords/{id}', 'API\MakeSalesController@salesRecordEdit');


Route::get('getTransactionId/{type}', 'API\GeneralController@getTransactionId');

//purchase
Route::post('createPurchase', 'API\MakePurchaseContoller@createPurchase');
Route::get('loadPurchase', 'API\MakePurchaseContoller@loadPurchase');
Route::get('purchaseRecord', 'API\MakePurchaseContoller@purchaseRecord');

Route::post('reverseSales/{id}', 'API\MakeSalesController@reverseSales');
Route::get('getTotalSalesRecords/{id}', 'API\MakeSalesController@getTotalSalesRecords');
Route::post('restoreSales/{id}', 'API\MakeSalesController@restoreSales');

Route::delete('deleteSales/{id}' , 'API\MakeSalesController@deleteSales');
Route::delete('deleteReverseSales/{id}' , 'API\MakeSalesController@deleteReverseSales');
Route::put('updateSales' , 'API\MakeSalesController@updateSales');


Route::get('profile', 'API\UsersController@profile');
Route::put('profile', 'API\UsersController@updateProfile');
Route::post('uploadPhoto','API\UsersController@uploadPhoto');
Route::post('changePassword', 'API\UsersController@changePassword');
Route:: post('passwordCheck', 'API\UsersController@passwordCheck');
Route:: get('getCost/{id}', 'API\ProductController@getCost');
Route:: get('findUser', 'API\UsersController@findUser');
Route::resource('chatcontrol', 'API\ChatsController');
Route::post('getMessage' , 'API\ChatsController@getMessage');
Route::post('getDetail' , 'API\ChatsController@getDetail');