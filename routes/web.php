<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contrata-online', [App\Http\Controllers\SubscriptionController::class, 'pricingShow'])->name('pricingShow');
Route::post('/checkout-session', [\App\Http\Controllers\SubscriptionController::class, 'createCheckoutSession'])->name('create_checkout_session');
Route::get('/success', function(){
    return 'success';
})->name('success');

Route::get('/cancel', function(){
    return 'cancel';
})->name('cancel');

Route::post('/webhook', [\App\Http\Controllers\SubscriptionController::class, 'webhook'])->name('webhook');