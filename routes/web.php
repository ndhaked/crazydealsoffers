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
Route::get(
    'call-migration',
    function () {
        \Artisan::call('migrate');
        return redirect()->back();
    }
);

// Route::fallback(function () {
//     return view('error');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pages/{slug}', [App\Http\Controllers\HomeController::class, 'staticPages'])->name('static.pages');
Route::get('/advertiseaffiliated/{slug}', [App\Http\Controllers\HomeController::class, 'advertiseAffiliated'])->name('advertiseaffiliated');

Route::get('/faqs', [App\Http\Controllers\HomeController::class, 'faqs'])->name('faqs');

Route::get('/social-login', [App\Http\Controllers\SocialController::class, 'socialLogin'])->name('socialLogin');
//Route::get('/blog', [App\Http\Controllers\HomeController::class, 'blog'])->name('blog');
//Route::get('/blog/details/{slug}', [App\Http\Controllers\HomeController::class, 'blogDetails'])->name('blog.details');

Route::post('/subscribe', [App\Http\Controllers\HomeController::class, 'subscrivedMailchimp'])->name('subscribe');

Route::any('/products', [App\Http\Controllers\HomeController::class, 'productListing'])->name('products');
Route::get('/products/{cat_slug}', [App\Http\Controllers\HomeController::class, 'productListing'])->name('category.products');
Route::get('/product/details/{slug}', [App\Http\Controllers\HomeController::class, 'productDetails'])->name('details');

Route::group(['prefix' => 'auth/facebook2'], function () {
    Route::get('/', [\App\Http\Controllers\SocialController::class, 'redirectToProvider'])->name('fbLogin');
    Route::get('/callback2', [\App\Http\Controllers\SocialController::class, 'handleProviderCallback']);
});

Route::get('/get-photos', 'IGController@getMedia');
Route::get('/ig-redirect-uri', [\App\Http\Controllers\IGController::class, 'igRedirectUri']);
Route::get('/pi-redirect-uri', [\App\Http\Controllers\IGController::class, 'piRedirectUri']);
Route::get('/pi-post', [\App\Http\Controllers\SocialController::class, 'postPinterestPin']);
Route::get('/ig-deauthorize', [\App\Http\Controllers\IGController::class, 'deauthorize']);
Route::get('/ig-data-deletion', [\App\Http\Controllers\IGController::class, 'dataDelete']);
Route::get('/ig-post', [\App\Http\Controllers\SocialController::class, 'publishInstagramImage']);
Route::get('/ig-account-id', [\App\Http\Controllers\SocialController::class, 'getInstagramAccountId']);