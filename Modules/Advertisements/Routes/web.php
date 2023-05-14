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
        Route::resource('advertisement', 'AdvertisementsController', ['names' => 'advertisement'])->except([]);
        //Upload Images By Ajax
        Route::get('advertisement/update-status/{id}', 'AdvertisementsController@status')->name('advertisement.status');
        Route::post('advertisement/media/upload', 'AdvertisementsController@saveMedia')->name('advertisement.uploadMedia');
        Route::get('advertisement/ajax/data', 'AdvertisementsController@getAjaxData')->name('advertisement.ajaxdata');
    });
});
