<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::any('/{model}/{method}/{additional?}', 'HomeController@locator');

Route::get('/{any}', function () {
    return view('web');
})->where('any', '.*');



