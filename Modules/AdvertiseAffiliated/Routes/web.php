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
        Route::resource('advertiseaffiliated', 'AdvertiseAffiliatedController', ['names' => 'advertiseaffiliated'])->except([]);
        //Upload Images By Ajax
        Route::get('advertiseaffiliated/update-status/{id}', 'AdvertiseAffiliatedController@status')->name('advertiseaffiliated.status');
        Route::post('advertiseaffiliated/media/upload', 'AdvertiseAffiliatedController@saveMedia')->name('advertiseaffiliated.uploadMedia');
        Route::get('advertiseaffiliated/ajax/data', 'AdvertiseAffiliatedController@getAjaxData')->name('advertiseaffiliated.ajaxdata');
    });
});