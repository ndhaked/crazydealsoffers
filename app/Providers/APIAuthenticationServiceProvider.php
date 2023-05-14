<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class APIAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       $this->getAuthRepo();
       $this->getUserRepo();
       $this->getBlogRepo();
       $this->getProductRepo();
       $this->getProductCommentRepo();
       $this->getStaticPageRepo();
       $this->getFavoriteRepo();
       $this->getLikeDislikeRepo();
    }

    //Use for Authentication Api
    public function getAuthRepo() {
        return $this->app->bind('App\Repositories\Auth\AuthenticationRepositoryInterface','App\Repositories\Auth\AuthenticationRepository');
    }

    //Use for UserProfile Api
    public function getUserRepo() {
        return $this->app->bind('App\Repositories\UserProfile\UserProfileRepositoryInterface','App\Repositories\UserProfile\UserProfileRepository');
    }

    //Use for Blogs Api
    public function getBlogRepo() {
        return $this->app->bind('App\Repositories\Blog\BlogRepositoryInterface','App\Repositories\Blog\BlogRepository');
    }

    //Use for Products Api
    public function getProductRepo() {
        return $this->app->bind('App\Repositories\Product\ProductRepositoryInterface','App\Repositories\Product\ProductRepository');
    } 

    //Use for Products Comment Api
    public function getProductCommentRepo() {
        return $this->app->bind('App\Repositories\Product\Comment\ProductCommentRepositoryInterface','App\Repositories\Product\Comment\ProductCommentRepository');
    }

    //Use for Static Pages Api
    public function getStaticPageRepo() {
        return $this->app->bind('App\Repositories\StaticPages\StaticPagesRepositoryInterface','App\Repositories\StaticPages\StaticPagesRepository');
    }

    //Use for UserFavoriteProduct Api
    public function getFavoriteRepo() {
        return $this->app->bind('App\Repositories\Favorite\FavoriteRepositoryInterface','App\Repositories\Favorite\FavoriteRepository');
    }

    //Use for UserLikeDislikeProduct Api
    public function getLikeDislikeRepo() {
        return $this->app->bind('App\Repositories\LikeDislike\LikeDislikeRepositoryInterface','App\Repositories\LikeDislike\LikeDislikeRepository');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
