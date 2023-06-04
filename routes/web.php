<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CustomerProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::resource('roles', RoleController::class);



// Admin
Route::get('/admin/product/list-product', [AdminProductController::class, 'index'])->name('product.list-product');
Route::get('/admin/product/add-product', [AdminProductController::class, 'add']);
Route::post('/admin/product/store-product', [AdminProductController::class, 'store']);
Route::post('/admin/product/{id}/edit-product', [AdminProductController::class, 'edit']);
Route::put('/admin/product/{id}/update-product', [AdminProductController::class, 'update']);
Route::delete('/admin/product/{id}/destroy-product', [AdminProductController::class, 'destroy']);


// Customer
Route::get('/customer/list-product', [CustomerProductController::class, 'index']);
Route::post('/customer/store-product/{id}', [CustomerProductController::class, 'store']);
Route::patch('/customer/product/{id}/update', [CustomerProductController::class, 'update']);
Route::get('/customer/list-history', [CustomerProductController::class, 'myHistory']);
Route::get('/customer/product/{id}/detail-product', [CustomerProductController::class, 'detail']);


