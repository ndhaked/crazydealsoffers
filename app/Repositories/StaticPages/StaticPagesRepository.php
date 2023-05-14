<?php

namespace App\Repositories\StaticPages;

use DB,Mail;
use config,File;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\StaticPages\Entities\StaticPages;
use Modules\Configuration\Entities\Configuration;

class StaticPagesRepository implements StaticPagesRepositoryInterface {

    function __construct(StaticPages $Pages,Configuration $Configuration) {
       $this->Pages = $Pages;
       $this->Configuration = $Configuration;
    }

    public function getCmsPagesData($slug,$request)
    {
        $record = $this->Pages->where('slug',$slug)->select('slug','static_pages.name_en','static_pages.description_en','banner_image','updated_at')->first();
        $response['status_code'] = 200;
        $response['message'] = 'Cms Page Data';
        if($record){
            $response['data']['slug'] = $record->slug;
            $response['data']['title'] = $record->name_en;
            $response['data']['description'] = $record->description_en;
            $response['data']['last_update'] = $record->updated_at->format(config::get('custom.backed_campaign_formate'));
            if($record->slug=='aboutus'){
                $response['data']['banner_image'] = $record->BannerPath;
            }
        } else {
            $response['status_code'] = 404;
            $response['message'] = 'There is no record found.';
            $response['data'] = array();
        }

        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }    

    public function getSocialLinksData($request)
    {
        $records = Configuration::whereIn('slug',['facebook','instagram','tiktok'])->get();
        $response['status_code'] = 200;
        $response['message'] = 'Social Link Data';
        if(!empty($records)) {
            foreach($records as $item) {
                $response['data'][$item['slug']] = array('title' => $item['config_title'], 'value' => $item['config_value']);
            }
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }

    public function getPlaystoreLinks($request)
    {
        $records = Configuration::whereIn('slug',['ios-app-url','android-app-url'])->get();
        $response['status_code'] = 200;
        $response['message'] = 'Playstore Link Data';
        if(!empty($records)) {
            foreach($records as $item) {
                if($item['slug']=='android-app-url'){
                    $response['data']['androidappurl'] = array('title' => $item['config_title'], 'value' => $item['config_value']);
                }
                if($item['slug']=='ios-app-url'){
                    $response['data']['iosappurl'] = array('title' => $item['config_title'], 'value' => $item['config_value']);
                }
                
            }
        }
        return response()->json($response, $response['status_code'])->withHeaders(checkVersionStatus($request->headers->get('Platform'), $request->headers->get('Version')))->setStatusCode($response['status_code']);
    }
}