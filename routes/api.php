<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EventController;
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
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('/forgetPassword',[\App\Http\Controllers\Auth\ForgotPasswordController::class,'sendResetLinkEmail']);




Route::middleware('auth:api')->group( function () {
Route::get('/getUser/{id?}',[AuthController::class,'getUser']);
Route::post('/changePassword',[AuthController::class,'changePassword']);
Route::post('/editUser',[AuthController::class,'editUser']);
Route::post('/addContact',[ContactController::class,'addContact']);
Route::get('/contactlist',[ContactController::class,'contactlist']);
Route::post('/addEvent',[EventController::class,'addEvent']);
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
