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
	Route::middleware(['web'])->group(function () {
        Route::resource('email-templates', 'EmailTemplatesController', ['names' => 'email-templates'])->except([
		   'show'
		]);
		Route::get('email-templates/ajax/data', 'EmailTemplatesController@getAjaxData')->name('email-templates.ajaxdata');
    });
});
