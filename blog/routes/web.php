<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'ImageUploadController@home');

Route::post('/upload/images', [
  'uses'   =>  'ImageUploadController@uploadImages',
  'as'     =>  'uploadImage'
]);
Route::get('/delete/{id}','ImageUploadController@deleteimage');
Route::get('/video','ImageUploadController@videoupload');
Route::post('/upload/video','ImageUploadController@savevideo')->name('videoupload');
Route::get('/video/delete/{id}','ImageUploadController@deletevideo')->name('delete-video');
Route::get('/images/multiple','ImageUploadController@uploadmultipleImages')->name('multiple');
Route::post('/images/submit','ImageUploadController@submitmultiple')->name('submit');
