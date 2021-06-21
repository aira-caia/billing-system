<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
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

Route::resource('payment', PaymentController::class)->only("store", "show");
Route::get("orders", [PaymentController::class, "orders"]);

Route::get("receipt/{q}", [PaymentController::class, "receipt"]);
Route::get("receipt/v2/{q}", [PaymentController::class, "receiptWeb"]);

Route::resource('menu', \App\Http\Controllers\MenuController::class)->only('index');
Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only('index');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], static function () {
    Route::resource('menu', \App\Http\Controllers\MenuController::class)->only('store', 'update', 'destroy');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only('store', 'destroy');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::resource('user', AuthController::class)->only('index', 'update');
    Route::get("payments", [PaymentController::class, "payments"]);
    Route::get("payments/v2", [PaymentController::class, "webPayments"]);
    Route::get("home", [\App\Http\Controllers\AuthController::class, "home"]);
    Route::resource('payment', PaymentController::class)->only("update");
    Route::resource('report', \App\Http\Controllers\ReportController::class)->only("index");
});
