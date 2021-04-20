<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


Route::resource('menu', \App\Http\Controllers\MenuController::class)->only('index');
Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only('index');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], static function () {
    Route::resource('menu', \App\Http\Controllers\MenuController::class)->only('store', 'update', 'destroy');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});
