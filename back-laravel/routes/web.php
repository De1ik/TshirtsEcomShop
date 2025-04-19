<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PageCatalogueController;
use App\Http\Controllers\AdminCreateProductController;
use App\Http\Controllers\AdminUpdateProductController;
use App\Http\Controllers\AdminProductsCatalogueController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('', [MainController::class, 'index'])->name('home');

Route::get('/catalogue', [PageCatalogueController::class, 'default'])->name('default_catalogue');
Route::post('/catalogue', [PageCatalogueController::class, 'custom_filters'])->name('custom_filters');

Route::get('/admin/create-product', [AdminCreateProductController::class, 'create_product'])->name('create_product');
Route::post('/admin/create-product', [AdminCreateProductController::class, 'save_new_product'])->name('save_new_product');

Route::get('/admin/products/update/{id}', [AdminUpdateProductController::class, 'index'])->name('update_product_index');
Route::put('/admin/products/update/product/{id}', [AdminUpdateProductController::class, 'update_product'])->name('update_product');
Route::put('/admin/products/update/product/{id}/variant', [AdminUpdateProductController::class, 'update_variant'])->name('update_variant');
Route::delete('/admin/products/delete/variant/{id}', [AdminUpdateProductController::class, 'delete_variant'])->name('delete_variant');
Route::delete('/admin/products/delete/product/{id}', [AdminUpdateProductController::class, 'delete_product'])->name('delete_product');


Route::get('/admin/products-catalogue', [AdminProductsCatalogueController::class, 'default'])->name('admin_default_catalogue');
Route::get('profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');
Route::post('profile', [UserController::class, 'update'])->middleware('auth')->name('profile.update');
