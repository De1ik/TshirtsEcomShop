<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PageCatalogueController;
use App\Http\Controllers\AdminCreateProductController;
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
Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::get('/admin/create-product', [AdminCreateProductController::class, 'create_product'])->name('create_product');
Route::post('/admin/create-product', [AdminCreateProductController::class, 'save_new_product'])->name('save_new_product');
