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
use Modules\Admin\Http\Controllers\AdminLoginController;
use Modules\Admin\Http\Controllers\AdminForgotPasswordController;
use Modules\Admin\Http\Controllers\AdminResetPasswordController;

Route::prefix('admin')->group( function() {
	Route::middleware(['guest'])->group(function () {
        Route::get('login', 'AdminLoginController@getAdminLogin')->name('admin.login');
        Route::post('login', 'AdminLoginController@adminAuth')->name('admin.auth');

        Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
	    Route::get('/password/reset',  [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
	    Route::post('/password/reset', [AdminResetPasswordController::class, 'reset'])->name('admin.password.reset');
	    Route::get('/password/reset/{token}', [AdminResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    	Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    });
});


