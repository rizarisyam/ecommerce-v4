<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Customer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [HomeController::class, 'index']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware('role:admin')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('/carts', CartController::class)->only(['index']);
    Route::resource('/orders', OrderController::class);
    Route::resource('/customer', CustomerController::class);
});

Route::get('/carts/shipment/', [CartController::class, 'shipment'])->name('carts.shipment');
Route::resource('/categories', CategoryController::class);
Route::resource('/products', ProductController::class)->only(['index', 'store']);
Route::resource('/expeditions', ExpeditionController::class)->only(['index', 'store']);

Route::prefix('user')->group(function () {
    Route::get('/purchase', [AccountController::class, 'purchase'])->name('user.purchase');
    Route::get('/address', [AccountController::class, 'address'])->name('user.address');
    Route::resource('/account', AccountController::class)->middleware('auth');
});

require __DIR__ . '/auth.php';
