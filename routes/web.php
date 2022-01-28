<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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
   Route::get('/carts/{id}/shipment/', [CartController::class, 'shipment'])->name('carts.shipment');
});

Route::resource('/categories', CategoryController::class)->only(['index', 'store']);
Route::resource('/products', ProductController::class)->only(['index', 'store']);
Route::resource('/expeditions', ExpeditionController::class)->only(['index','store']);

require __DIR__.'/auth.php';
