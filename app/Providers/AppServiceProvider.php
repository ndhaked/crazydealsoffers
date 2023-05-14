<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ViewComposers\GlobalDataComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       GlobalDataComposer::sendSocialLinkData();
    }
}
