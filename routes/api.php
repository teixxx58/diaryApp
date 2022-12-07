<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Models\uploadimage;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('photo', 'App\Http\Controllers\PhotoController@index');    
Route::post('st', 'App\Http\Controllers\PhotoController@store');
Route::get('show/{id}', 'App\Http\Controllers\PhotoController@show');
Route::post('update', 'App\Http\Controllers\PhotoController@update');        
Route::post('delete/{id}', 'App\Http\Controllers\PhotoController@destroy');