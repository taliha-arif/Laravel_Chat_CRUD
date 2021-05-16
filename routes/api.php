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
use App\Http\Controllers\users;
use App\Http\Controllers\chatting;
use App\Http\Middleware\verifyuser;
//securirty in laravel
Route::post('/signup', [users::class, 'signup']);
Route::post('/login', [users::class, 'login']);
Route::post('/forgotpassword', [users::class, 'forgetpassword']);
Route::post('/resetpassword/{links}', [users::class, 'resetpassowrd']);

Route::middleware([verifyuser::class])->group(function () {
    Route::post('/sendmessage', [chatting::class, 'message']);
    Route::get('/logout', [users::class, 'logout']);
    Route::post('/singledeletemessage', [chatting::class, 'delete']);
    Route::post('/retrivetwouserschat', [chatting::class, 'read']);
    Route::post('/updatemessage', [chatting::class, 'update']);

});
