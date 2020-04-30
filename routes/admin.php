<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group.
|
*/

Route::prefix('admin')->group(function () {

    // auth routes
    Route::post('login', 'AdminController@login')->middleware('admin');
    Route::get('logout', 'AdminController@logout')->middleware('admin');

    // return admin main view page
    Route::get('/{any}', function () {
        return view('admin');
    })->middleware('admin');

    // main controller
    Route::any('/{model}/{method}/{additional?}', 'AdminController@locator')->middleware('admin');

});
