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
// 09687733837
Route::get('/test', function () {
    echo base64_encode('pk-09687733837:');
});

Route::view('/{any?}', 'index')->where('any', '.*');
