<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');
Route::get('living', 'LivingController@index');
Route::get('saving', 'SavingController@index');
Route::get('playing', 'PlayingController@index');

Route::get('api/living/all', 'LivingController@apiAll');
Route::post('api/living/create', 'LivingController@apiCreate');
Route::get('api/living/{id}', 'LivingController@apiFind');
Route::post('api/living/{id}/update', 'LivingController@apiUpdate');
Route::post('api/living/paid/{id}', 'LivingController@apiPaid');
Route::post('api/living/{id}/create', 'LivingController@apiCreateItem');
Route::post('api/living/item/{id}', 'LivingController@apiUpdateItem');
Route::get('api/living/delete/{id}', 'LivingController@apiDeleteItem');
Route::get('api/living/{id}/delete', 'LivingController@apiDestroy');
Route::post('api/living/{id}/duplicate', 'LivingController@apiDuplicate');

Route::post('api/upload/{category}', 'HomeController@apiUploadFile');
