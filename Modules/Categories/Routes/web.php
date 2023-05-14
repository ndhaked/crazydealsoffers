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

Route::prefix('admin')->group(function() {
	Route::group(['middleware' => ['web','auth']], function () {
		Route::resource('categories', 'CategoriesController')->except([
		   'show'
		]);
		Route::get('categories/status/{slug}', 'CategoriesController@status')->name('categories.status');
		Route::post('categories/media/upload', 'CategoriesController@saveMedia')->name('categories.uploadMedia');
    });
});
