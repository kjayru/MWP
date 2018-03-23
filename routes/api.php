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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


	Route::resource('contactos','ContactoController',['only'=>['index','create','store','show']]);
   
    Route::resource('users', 'UserController', ['except' => ['create', 'edit']]);
    
    Route::name('verify')->get('users/verify/{token}', 'UserController@verify');
    Route::name('resend')->get('users/{user}/resend', 'UserController@resend');

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');


    