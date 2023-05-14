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
        Route::resource('blogs', 'BlogsController',	['names' => 'blog'])->except([]);
		//Upload Images By Ajax
    	Route::get('blog/update-status/{id}', 'BlogsController@status')->name('blog.status');
    	Route::post('blog/media/upload', 'BlogsController@saveMedia')->name('blog.uploadMedia');
    	Route::get('blog/ajax/data', 'BlogsController@getAjaxData')->name('blog.ajaxdata');
    	Route::get('blog/remove-image/{id}', 'BlogsController@removeImage')->name('blog.image.remove');
    });
});