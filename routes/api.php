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

Route::resource('menu', \App\Http\Controllers\MenuController::class)->only('index','show');
Route::resource('categories', \App\Http\Controllers\CategoryController::class)->only('index');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::resource('company',\App\Http\Controllers\CompanyInfoController::class);
Route::get('notify', [\App\Http\Controllers\MenuController::class, 'notify']);
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
    Route::put('/inventory',[\App\Http\Controllers\MenuController::class, 'inventory']);
});
/*

Route::post('test', function () {
    $gateway = new Braintree\Gateway([
        'environment' => 'sandbox',
        'merchantId' => 'mhjfnw88grqcb72s',
        'publicKey' => 'snk3hfq997gxjx53',
        'privateKey' => '89ba1cfaebef49533c19e1d02e5d0523'
    ]);

    $transaction = $gateway->transaction()->sale([
        'amount' => '102.00',
        'paymentMethodNonce' => '2f6e9477-bce3-08f3-71e8-ddb8ab11028c',
        'deviceData' => 'default',
        'options' => [
            'submitForSettlement' => True
        ]
    ]);
//    $test = $gateway->paymentMethodNonce()->find('8ca396a3-5ed4-083b-6c88-a056f76ac2ca');
//    $test = $gateway->transaction()->find('gcn78p6k');
//    dd($test);

//    $data = $gateway->transaction()->find('8bcca629-4504-0586-719f-b8e00fde1625');
    return response(['test' => $transaction]);
});*/
