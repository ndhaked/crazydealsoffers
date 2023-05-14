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
		Route::get('products/social-login', [App\Http\Controllers\SocialController::class, 'socialLogin'])->name('product.socialLogin');
		Route::get('/pi-redirect-uri', [\App\Http\Controllers\IGController::class, 'piRedirectUri']);
		Route::get('/ig-deauthorize', [\App\Http\Controllers\IGController::class, 'deauthorize']);
		Route::get('/ig-data-deletion', [\App\Http\Controllers\IGController::class, 'dataDelete']);
		Route::get('/ig-account-id', [\App\Http\Controllers\SocialController::class, 'getInstagramAccountId']);
		Route::get('auth/facebook2/', [\App\Http\Controllers\SocialController::class, 'redirectToProvider'])->name('product.fbLogin');
		Route::get('auth/facebook2/callback2', [\App\Http\Controllers\SocialController::class, 'handleProviderCallback']);
        Route::resource('products', 'ProductsController',	['names' => 'product'])->except([]);
       

		//Upload Images By Ajax
    	Route::get('product/update-status/{id}', 'ProductsController@status')->name('product.status');
    	Route::post('product/media/upload', 'ProductsController@saveMedia')->name('product.uploadMedia');
    	Route::get('product/exportcsv', 'ProductsController@exportcsv')->name('product.exportcsv');
    	Route::get('product/uploadcsv', 'ProductsController@uploadcsv')->name('product.uploadcsv');
    	Route::post('product/importcsv', 'ProductsController@importcsv')->name('product.importcsv');
    	Route::get('product/deal-of-the-day/{id}/{status}', 'ProductsController@dealFTheDay')->name('product.deal_of_the_day');
    	Route::get('product/samplecsv', 'ProductsController@sampleCSV')->name('product.samplecsv');
		
		Route::get('product/autocomplete/get-deals', 'ProductsController@getSuggessionDeals')->name('product.getSuggessionDeals');
    	Route::get('product/removeinactive', 'ProductsController@removeInactive')->name('product.removeinactive');
    });
});