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
        Route::resource('permissions', 'PermissionsController')->except([
		   'show','store','delete'
		]);
		Route::get('permissions/ajax/data', 'PermissionsController@getAjaxData')->name('permissions.ajaxdata');
		Route::get('roles/permission/{slug}', 'PermissionsController@getPermission')->name('roles.premission.create');
    	Route::post('roles/permission/{slug}', 'PermissionsController@postPermission')->name('roles.premission.store');
    });
});
