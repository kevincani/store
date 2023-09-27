<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/', 'as'=> 'home.'], function (){
    Route::get('', 'HomeController@view')->name('index');
    Route::get('/filter', 'HomeController@filter')->name('filter');
});

Route::group(["middleware" => "auth"], function(){
    Route::middleware(['role:admin'])->group(function () {
        Route::group(['prefix' => 'admin', 'as'=> 'admin.'], function (){
            Route::get('/', 'AdminController@view')->name('index');
            Route::get('/raport','AdminController@raport')->name('raport');
        });

        Route::group(['prefix' => 'user', 'as'=> 'user.'], function (){
            Route::get('user', 'UserController@getForDatatable')->name('datatable');
        });
        Route::resource('user', 'UserController')->except('create','show','edit');
    });

    Route::middleware(['role:admin|manager'])->group(function (){
        Route::group(['prefix' => 'product', 'as'=> 'product.'], function (){
            Route::get('datatable', 'ProductController@getForDatatable')->name('datatable');
        });
        Route::resource('product', 'ProductController');

        Route::resource('image', 'ImageController')->only('delete');

        Route::group(['prefix' => 'inventary', 'as'=> 'inventary.'], function (){
            Route::get('inventary', 'InventaryController@getForDatatable')->name('datatable');
        });
        Route::resource('inventary', 'InventaryController');
    });

    Route::group([], function () {
        Route::group(['prefix' => 'details', 'as'=> 'home.'], function (){
            Route::get('{id}', 'HomeController@viewDetails')->name('details');
            Route::post('{id}', 'HomeController@checkInventary')->name('checkInventary');
        });
        Route::group(['prefix' => 'cart', 'as'=> 'cart.'], function (){
            Route::get('', 'CartController@view')->name('view');
            Route::post('', 'CartController@show')->name('show');
        });
        Route::group(['prefix' => '/profile', 'as'=> 'profile.'], function (){
            Route::get('', 'ProfileController@edit')->name('edit');
            Route::patch('', 'ProfileController@update')->name('update');
            Route::delete('', 'ProfileController@destroy')->name('destroy');
        });
        Route::group(['prefix' => 'order', 'as'=> 'order.'], function (){
            Route::get('', 'OrderController@index')->name('index');
            Route::get('datatable', 'OrderController@getForDatatable')->name('datatable');
            Route::get('refund{id}', 'OrderController@refund')->name('refund');
        });
        Route::group(['prefix' => '/', 'as'=> 'checkout.'], function (){
            Route::post('session', 'StripeController@session')->name('session');
            Route::get('success/{order}', 'StripeController@success')->name('success');
            Route::get('cancel/{order}', 'StripeController@cancel')->name('cancel');
            Route::post('webhook', 'StripeController@handleWebhook')->name('webhook');
        });


    });
});



require __DIR__.'/auth.php';
