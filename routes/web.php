<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');
Route::get('/track-order', 'HomeController@search')->name('orders.track');

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::resource('users', 'UserController')->except(['show', 'destroy']);
    Route::resource('orders', 'OrderController');

    Route::get('/archived-orders', 'ArchivedOrderController@index')->name('archived-orders.index');
    Route::get('/archived-orders/{id}', 'ArchivedOrderController@show')->name('archived-orders.show');
    Route::patch('/archived-orders/{id}/restore', 'ArchivedOrderController@restore')->name('archived-orders.restore');
});
