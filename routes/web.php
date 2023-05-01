<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '', 'namespace' => 'App\Http\Controllers\Admin', 'as' => 'admin.'], function () {
    Route::get('login', function () {
        return view('admin.pages.auth.login');
    })->name('login');

    Route::post('login-post', 'AuthController@login')->name('login-post');

    Route::group(['middleware' => 'auth:admin'], function () {
        // logout
        Route::post('logout', 'AuthController@logout')->name('logout');

        /**
         * Admins Module Routes
         */
        Route::prefix('admins')->group(function () {
            Route::as('admins.')->group(function () {
                Route::get('data', 'AdminController@data')->name('data');
            });
        });
        Route::resource('admins', 'AdminController');
    });
});
