<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogoController;
use Illuminate\Support\Facades\Auth;


Route::prefix('admin/category')->name('admin.category.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
});


Route::prefix('admin/product')->name('admin.product.')->group(function () {
    Route::get('/', [ProductController::class, 'indexadmin'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
});

Route::prefix('/admin/logo')->name('admin.logo.')->group(function () {
    Route::get('/', [LogoController::class, 'index'])->name('index');
    Route::get('/create', [LogoController::class, 'create'])->name('create');
    Route::post('/store', [LogoController::class, 'store'])->name('store');
    Route::get('/admin/logo/{id}/toggle-active', [LogoController::class, 'index'])->name('toggleActive');});

Route::prefix('/admin')->name('admin.index')->group(function () {
    Route::get('/', [Controller::class, 'index'])->name('index');
});


//route user
Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');



// Route đăng ký
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Route đăng nhập
Route::get('/login', [LoginController::class, 'LoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Route đăng xuất
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


