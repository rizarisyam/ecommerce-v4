<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\ExpeditionController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserAddressController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\UserAddress;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth')->group(function () {
});
Route::name('api.')->group(function () {
    Route::get('/carts/shipment', [CartController::class, 'shipment'])->name('carts.shipment');
    Route::put('/carts/shipment', [CartController::class, 'storeShipment'])->name('carts.store.shipment');
    Route::patch('/carts/checkout-pay/{id}', [CartController::class, 'checkoutPay'])->name('checkout.pay');
    Route::apiResource('carts', CartController::class);
    Route::post('/carts/checkout', [CartController::class, 'checkout'])->name('carts.checkout');



    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('expeditions', ExpeditionController::class);
    Route::apiResource('user-address', UserAddressController::class);
    Route::apiResource('/orders', OrderController::class);
    Route::apiResource('/customer', CustomerController::class);
});
Route::delete('carts/user/{id}', [CartController::class, 'deleteByProduct'])->name('cart.product');
