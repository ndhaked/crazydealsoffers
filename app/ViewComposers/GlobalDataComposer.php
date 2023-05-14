<?php

namespace App\ViewComposers;

use DB,Config;
use Illuminate\Support\Facades\Auth;
use Modules\Configuration\Entities\Configuration;
use Illuminate\Database\Eloquent\Builder;
use Modules\Products\Entities\ProductComment;
use App\Models\PushNotifications;

class GlobalDataComposer
{
    public static function sendSocialLinkData()
    {
        view()->composer(['layouts.footer','welcome','product_details','product_listing'], function ($view) {
            $records = Configuration::whereIn('slug',['facebook','instagram','twitter','pinterest','adminemail','admincontact','ios-app-url','android-app-url'])->get();
            $socialLinkData= NULL;
            if(!empty($records)) {
                foreach($records as $item) {
                    $socialLinkData[$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
                }
            }
            $view->with(compact('socialLinkData'));
        });

        view()->composer(['admin.page.header'], function ($view) {
            $totalUnreadNotificationCount = PushNotifications::orderBy('id','desc')->where('is_read',0)->whereNotIn('notification_type',['product'])->count();
            $latesNotifications = PushNotifications::orderBy('id','desc')->where('is_read',0)->whereNotIn('notification_type',['product'])->limit(20)->get();
            $view->with(compact('totalUnreadNotificationCount','latesNotifications'));
        });
    }
}