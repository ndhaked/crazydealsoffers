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
        Route::resource('notifications', 'NotificationsController', ['names' => 'notifications'])->except([
		   'show'
		]);
		Route::get('notifications/ajax/data', 'NotificationsController@getAjaxData')->name('notifications.ajaxdata');
		Route::get('notifications/autocomplete/get-deals', 'NotificationsController@getSuggessionDeals')->name('notifications.getSuggessionDeals');
		Route::get('notifications/autocomplete/get-users', 'NotificationsController@getUsersLists')->name('notifications.getUsersLists');

		Route::get('comment-notifications', 'CommentsNotificationsController@index')->name('commentnotifications.index');
		Route::get('product/comments/{slug}', 'CommentsNotificationsController@productComments')->name('commentnotifications.productComments');
		Route::get('comment/get-users','CommentsNotificationsController@getUsersListForTag')->name('commentnotifications.getUsersListForTag');
		Route::post('comment/add-comment','CommentsNotificationsController@addComments')->name('commentnotifications.addComments');
		Route::post('comment/add-comment-reply','CommentsNotificationsController@addCommentReply')->name('commentnotifications.addCommentReply');
		Route::resource('commentnotifications', 'CommentsNotificationsController',	['names' => 'commentnotifications'])->only('destroy');
    });
});


Route::middleware(['web'])->group(function () {
	Route::get('product-comments/{slug}', 'CommentsNotificationsController@getProductsCommnetsForFront')->name('front.getProductsCommnetsForFront');
});


