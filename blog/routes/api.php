<?php

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

Route::post('/register','Api\AuthController@register');
Route::post('/login','Api\AuthController@login');
Route::group(['middleware'=>'auth:api','namespace'=>'Api'],function(){
    Route::get('/logout','AuthController@logoutApi');
    Route::post('/change-password','AuthController@changePassword');

});
Route::group(['middleware'=>'auth:api','namespace'=>'Api'],function(){
   Route::get('/profile','ProfileController@index');
   Route::post('/update/profile_pic',"ProfileController@updateprofilepic");
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();

// });


