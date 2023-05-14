<?php

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
Route::prefix('admin')->group( function() {
	Route::group(['namespace' => 'BackEnd', 'middleware' => ['web','auth']], function () {
        Route::resource('subadmin',   'SubadminController',	['names' => 'subadmin'])->except([]);
        Route::resource('customers', 'UsersController',	['names' => 'users'])->except(['create','store','destroy']);
		//Upload Images By Ajax
    	Route::get('user/update-status/{slug}', 'UsersController@status')->name('users.status');
    	Route::post('user/media/upload', 'UsersController@saveMedia')->name('users.uploadProfile');
    	Route::post('user/user-change-password', 'UsersController@storeChangeUserPassword')->name('users.storeChangeUserPassword');
    });
});