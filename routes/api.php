<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version(['v1'],['namespace' => 'App\Http\Controllers\Api','middleware' =>'app_version'],  function ($api)
{
    $api->post('login','Auth\AuthenticationController@login');
    //Register Users With an Email ,Password and assign role dynamically
    $api->post('register','Auth\AuthenticationController@register');
    //Resend otp by email id
    $api->post('resend-otp','Auth\AuthenticationController@resendOtp');

    //Send Reset Password OTP on Email address
    $api->post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
    //Reset Passowrd with forgot otp bia Email
    $api->post('password/reset','Auth\ResetPasswordController@reset'); 

    $api->post('socia-login','Auth\AuthenticationController@socailLogin');

    //Guest Register
    $api->post('guest-register','Auth\AuthenticationController@guestRegister');
  
});

$api->version(['v1'],['namespace' => 'App\Http\Controllers\Api'],  function ($api)
{
     $api->get('pages/{slug}','StaticPages\StaticPagesController@getCmsPages');
     $api->get('pages/faq','StaticPages\StaticPagesController@getFaqData');
     $api->get('send-push-notification','Notifications\PushNotificationController@sendPushNotification');
     $api->get('get-social-links','StaticPages\StaticPagesController@getSocialLinks');
     $api->get('get-playstore-links','StaticPages\StaticPagesController@getPlaystoreLinks');

    $api->get('make-status-expired-deals','Product\ProductController@setCronmakeStatusExpiredDeals');

    $api->get('send-fcm-notification-for-all-users','Notifications\PushNotificationController@sendFcmNotificationForAllUsers');
    $api->get('delete-old-user-notifications','Notifications\PushNotificationController@deleteOldUserotifications');

    $api->post('get-users','Product\ProductCommentController@getUsersListForTag');
});

//Convert Auth to Guest Also
$api->version(['v1'],['namespace' => 'App\Http\Controllers\Api'],  function ($api)
{
    $api->post('product-list','Product\ProductController@productList');
    $api->post('product-detail','Product\ProductController@productDetail');
    $api->post('deal-off-the-day','Product\ProductController@dealOffTheDayList');

    //Product comment
    $api->post('add-comment','Product\ProductCommentController@addComment');
    $api->post('product-comments','Product\ProductCommentController@getProductComments');
    // CommentLikeDislike
    $api->post('comment-like-dislike','Product\ProductCommentController@addCommentLikeDislike');
    $api->post('add-comment-reply','Product\ProductCommentController@addCommentReply');
    $api->post('get-comments-reply','Product\ProductCommentController@getCommentsReply');

    // Blogs
    $api->post('blog-list','Blog\BlogController@blogList');
    $api->post('blog-detail','Blog\BlogController@blogDetail');

      //Categories  
    $api->post('category-list','Product\ProductController@categoryList');

    $api->post('get-homepage-data', 'UserProfile\UserController@getHomepageData');
    $api->post('get-autocomplete-data', 'UserProfile\UserController@getAutocompleteData');

     // Favourite
    $api->post('favorite-add-remove','Favorite\FavoriteController@addFavoriteUnfavroite');
    $api->post('favorite-list','Favorite\FavoriteController@listFavorite');

     // LikeDislike
    $api->post('like-dislike','LikeDislike\LikeDislikeController@addLikeDislike');

     //Notification list
    $api->post('notification-list','Notifications\PushNotificationController@getPushNotifications');
    $api->post('mark-to-read-notification','Notifications\PushNotificationController@markReadNotifiction');

    $api->post('update-notification-status', 'UserProfile\UserController@updateNotificationStatus');
});


$api->version(['v1'],['namespace' => 'App\Http\Controllers\Api','middleware' => ['jwt.verify','auth:api','app_version']],  function ($api)
{
    //Use api for Auth
	$api->post('logout','Auth\AuthenticationController@logout');
	$api->post('refresh','Auth\AuthenticationController@refresh');
    //User Profile 
	$api->get('user-profile','UserProfile\UserController@userProfile');
    $api->post('user-update', 'UserProfile\UserController@userUpdate');
    $api->post('user-changepassword', 'UserProfile\UserController@userChangePassword');
    
    $api->post('delete-account-permanently', 'UserProfile\UserController@deleteAccountPermanently');
    // Check Signed in url API
    $api->post('signed-url','UserProfile\UserController@signedURL');
   
});
//user this link for jwt auth
//https://www.tutsmake.com/laravel-8-jwt-rest-api-authentication-example-tutorial/
//https://www.positronx.io/laravel-jwt-authentication-tutorial-user-login-signup-api/