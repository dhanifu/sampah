<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-type', 'Api\TypeController@index')->name('api.get-type');
Route::get('get-type-object', 'Api\TypeController@show')->name('api.get-type-object');

Route::post('get-member', 'Api\MemberController@index')->name('api.get-member');