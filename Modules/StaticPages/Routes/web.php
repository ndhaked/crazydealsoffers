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
	Route::middleware(['web','auth'])->group(function () {
		Route::resource('staticpages', 'StaticPagesController')->except([
		   'show','destroy'
		]);
		//Upload Images By Ajax
		Route::post('staticpages/media/upload', 'StaticPagesController@saveMedia')->name('staticpages.mediaStore');
    });
});

Route::group(
	[
		//'prefix' => LaravelLocalization::setLocale(),
		//'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
	], 
function()
{
	//Route translate middleware
	Route::get('faq', 'Frontend\FrontStaticPageController@faq')->name('frontend.faq');
	Route::get('aboutus', 'Frontend\FrontStaticPageController@aboutus')->name('frontend.aboutus');
	Route::get('how-it-works', 'Frontend\FrontStaticPageController@howItWork')->name('frontend.howItWork');
	Route::get('privacy-policy', 'Frontend\FrontStaticPageController@privacyPolicy')->name('frontend.privacyPolicy');
	Route::get('terms-and-condition', 'Frontend\FrontStaticPageController@termAndConditions')->name('frontend.termAndConditions');
	//Route::get('{page}', 'Frontend\FrontStaticPageController@show')->name('pages.show');
});


Route::get('{productslug}', [App\Http\Controllers\HomeController::class, 'productDetails'])->name('productDetails');