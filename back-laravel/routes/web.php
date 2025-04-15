<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PageCatalogueController;
use App\Http\Controllers\AdminCreateProductController;
use App\Http\Controllers\AdminProductsCatalogueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('', [MainController::class, 'index'])->name('home');

Route::get('/catalogue', [PageCatalogueController::class, 'default'])->name('default_catalogue');
Route::post('/catalogue', [PageCatalogueController::class, 'custom_filters'])->name('custom_filters');

Route::get('/admin/create-product', [AdminCreateProductController::class, 'create_product'])->name('create_product');
Route::post('/admin/create-product', [AdminCreateProductController::class, 'save_new_product'])->name('save_new_product');

Route::get('/admin/products-catalogue', [AdminProductsCatalogueController::class, 'default'])->name('admin_default_catalogue');
Route::get('profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');
Route::post('profile', [UserController::class, 'update'])->middleware('auth')->name('profile.update');
