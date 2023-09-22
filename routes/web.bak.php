<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WelcomeController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [WelcomeController::class, 'view'])->name('welcome');
Route::middleware('auth')->get('/products/{id}', [WelcomeController::class, 'viewDetails'])->name('products.details');
Route::middleware('auth')->post('/products/{id}', [WelcomeController::class, 'checkInventary'])->name('products.checkInventary');
Route::get('/filtered', [WelcomeController::class, 'filterCategory'])->name('products.filterCategory');
Route::get('/cart', [WelcomeController::class, 'cart'])->name('products.cart');
Route::post('/cart', [WelcomeController::class, 'cartDisplay'])->name('products.cartDisplay');


Route::post('/session', [StripeController::class, 'session'])->name('checkout.session');
Route::get('/success/{order}', [StripeController::class, 'success'])->name('checkout.success');
Route::get('/cancel/{order}', [StripeController::class, 'cancel'])->name('checkout.cancel');
Route::post('/webhook', [StripeController::class, 'handleWebhook'])->name('checkout.webhook');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Route::get('/admin', [AdminController::class,'view'])->middleware(['auth', 'role:admin'])->name('admin.index');
//Route::get('/users', [UsersController::class,'view'])->middleware(['auth', 'role:admin'])->name('users.index');
Route::group(["middleware" => "auth"], function(){
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'view'])->name('admin.index');
        Route::get('/users', [UsersController::class, 'view'])->name('users.index');
        Route::post('/users/', [UsersController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{id}/', [UsersController::class, 'edit'])->name('users.edit');
        Route::post('users/update', [UsersController::class, 'update'])->name('users.update');
        Route::get('users/destroy/{id}/', [UsersController::class, 'destroy']);
    });

    Route::middleware(['role:admin|manager'])->group(function (){
        Route::get('/products',[ProductsController::class, 'view'])->name('products.view');
        Route::get('/addProduct',[ProductsController::class, 'viewAddProduct'])->name('addProduct.view');
        Route::post('/products',[ProductsController::class, 'store'])->name('products.store');
        Route::post('/addProduct',[ProductsController::class, 'add'])->name('addProduct.store');
        Route::get('/editProducts',[ProductsController::class, 'viewDatatable'])->name('editProducts.view');
        Route::get('/editProducts/edit/{id}/',[ProductsController::class, 'edit'])->name('products.edit');
        Route::post('/editProducts/update/',[ProductsController::class, 'update'])->name('products.update');
        Route::get('/editProducts/destroy/{id}/',[ProductsController::class, 'delete'])->name('products.delete');
        Route::get('/raport',[AdminController::class, 'raport'])->name('products.raport');

    });


    Route::group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/purchased', [UsersController::class, 'list'])->name('user.purchased');
        Route::get('/refund', [UsersController::class, 'refund'])->name('user.refund');
    });
});



require __DIR__.'/auth.php';
