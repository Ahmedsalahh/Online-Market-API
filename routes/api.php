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
// *************  Sprint 1 **********************
Route::post('user/register','APIRegisterController@register');
Route::get('allusers','APIRegisterController@getAllUsers');

// *************  Sprint 2 **********************
Route::post('user/login','APIRegisterController@login');

Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return auth()->user();
});
Route::get('users','APIRegisterController@getAllUsersbyAdmin');



