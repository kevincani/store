<?php

use Illuminate\Support\Facades\Route;



Route::get('/', 'WelcomeController@view')->name('welcome');
Route::get('/filtered', 'WelcomeController@filterCategory')->name('products.filterCategory');
Route::get('/cart', 'WelcomeController@cart')->name('products.cart');
Route::post('/cart', 'WelcomeController@cartDisplay')->name('products.cartDisplay');
Route::post('/session', 'StripeController@session')->name('checkout.session');
Route::get('/success/{order}', 'StripeController@success')->name('checkout.success');
Route::get('/cancel/{order}', 'StripeController@cancel')->name('checkout.cancel');
Route::post('/webhook', 'StripeController@handleWebhook')->name('checkout.webhook');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::group(["middleware" => "auth"], function(){
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', 'AdminController@view')->name('admin.index');
        Route::get('/raport','AdminController@raport')->name('admin.raport');

        Route::group(['prefix' => 'user', 'as'=> 'user.'], function (){
            Route::get('user', 'UserController@getForDatatable')->name('datatable');
        });
        Route::resource('user', 'UserController')->except('create','show','edit');
    });

    Route::middleware(['role:admin|manager'])->group(function (){
        Route::group(['prefix' => 'product', 'as'=> 'product.'], function (){
            Route::get('product', 'ProductController@getForDatatable')->name('datatable');
        });
        Route::resource('product', 'ProductController');

        Route::group(['prefix' => 'inventary', 'as'=> 'inventary.'], function (){
            Route::get('inventary', 'InventaryController@getForDatatable')->name('datatable');
        });
        Route::resource('inventary', 'InventaryController');

    });


    Route::group([], function () {
        Route::get('/products/{id}', 'WelcomeController@viewDetails')->name('products.details');
        Route::post('/products/{id}', 'WelcomeController@checkInventary')->name('products.checkInventary');
        Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
        Route::patch('/profile', 'ProfileController@update')->name('profile.update');
        Route::delete('/profile', 'ProfileController@destroy')->name('profile.destroy');
        Route::get('/purchased', 'UsersController@list')->name('user.purchased');
        Route::get('/refund', 'UsersController@refund')->name('user.refund');
    });
});

//Route::group(['prefix' => 'test', 'as'=> 'test.'], function (){
//    Route::post('user', 'TestController@getForDatatable')->name('datatable');
//});
//Route::resource('test', 'TestController');

require __DIR__.'/auth.php';
