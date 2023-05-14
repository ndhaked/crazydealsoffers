<?php

use App\Models\PushNotifications;
use Modules\Configuration\Entities\Configuration;
use Illuminate\Support\Facades\Storage;

if (! function_exists('app_name')) {
	/**
	 * Helper to grab the application name
	 *
	 * @return mixed
	 */
	function app_name() {
		return config('app.name');
	}
}

if ( ! function_exists('pr'))
{
    /**
     * Access the print_r helper
     */
    function pr($data)
    {
        echo "<pre>";
        print_r($data);die;
         
    }
}

if (!function_exists('onerrorReturnImage')) {
    function onerrorReturnImage()
    {
        return \URL::to('img/nouser.jpg');
    }
}


function get_guard(){
    if(Auth::guard('admin')->check())
        {return "admin";}
    elseif(Auth::guard('web')->check())
        {return "web";}
    else
        { return '';}
}

/*
** Display Button With Role
*/
if ( ! function_exists('displayButton'))
{
    function displayButton($buttonName=array())
    {
        $return = [];
        if( is_array($buttonName) &&  count($buttonName) > 0)
        {
            foreach ($buttonName as $key => $value)
            {
                $route = $value[0]; // modelName.function 
                $routeKey = isset($value[1])?$value[1]:[]; 
                $class = $routeKey[0]; 
                $return[$key] = buttonHtml($key, route($route, $routeKey),$class );
            }
        }
        return $return;
    }
}

/*
** Display social button
*/
if ( ! function_exists('socialIcons'))
{
    function socialIcons($slug)
    {
        $configuration = Configuration::where('slug',$slug)->first();
        return ($configuration) ? $configuration->config_value : '';
    }
}

/*
** Button With Html
*/
if ( ! function_exists('buttonHtml'))
{
    function buttonHtml($key, $link,$class)
    {
        $array = [
            "edit"=>"<a href='".$link."' title='Edit' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>",
            "Active"=>'&nbsp;&nbsp;<span class="margin-r-5"> <a id="Inactive_'.$class.'" data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Inactive" rel="Inactive" name="'.$link.'" href="javascript:;" OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Inactive" data-action="'.$link.'"><i class="fa fa-ban" aria-hidden="true"></i></a></span>',
            "Inactive"=>'&nbsp;&nbsp;<span class="margin-r-5"> <a id="Active_'.$class.'" data-toggle="tooltip" class="success tooltips"  title="Active"  rel="Active" name="'.$link.'" href="javascript:;" data-placement="top"  OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="'.$link.'"><i class="fa fa-check" aria-hidden="true"></i></a></span>',
            "add"=>'&nbsp;&nbsp;<a href="'.$link.'" class="tooltips" data-toggle="tooltip" data-placement="top"><i class="fa fa-eye"></i></a>',
            "delete"=>'
                <form method="POST" action="'.$link.'" accept-charset="UTF-8" style="display:inline" class="dele_'.$class.'">
                    <input name="_method" value="DELETE" type="hidden">
                    '.csrf_field().'
                        <span>
                             &nbsp;<a href="javascript:;" id="dele_'.$class.'" data-toggle="tooltip" title="Delete" type="button"  data-placement="top" name="Delete" class="delete_action tble_button_st tooltips" Onclick="return ConfirmDeleteLovi(this.id,this.name,this.name);" ><i class="fa fa-trash-o" title="Delete"></i>
                            </a>
                         </span>
                </form>',
            "deleteAjax"=>'&nbsp;<a href="javascript:;" id="dele_'.$class.'" data-toggle="tooltip" title="Delete" data-title="Delete" type="button"  data-placement="top" class="delete_ajax tble_button_st tooltips"  data-action="'.$link.'" onClick="return AjaxActionTableDrow(this);"><i class="fa fa-trash-o" title="Delete"></i></a>',
            "view"=>'&nbsp;&nbsp;&nbsp;<span class="margin-r-5"><a data-toggle="tooltip"  class="" title="View" href="'.$link.'"><i class="fa fa-eye" aria-hidden="true"></i></a> </span>',
             "images"=>'&nbsp;&nbsp;<span class="margin-r-5"><a data-toggle="tooltip" title="View Images" href="'.$link.'"><i class="fa fa-picture-o" aria-hidden="true"></i></a></span>',
             "pages"=>'<span class="margin-r-5"><a data-toggle="tooltip"  class="btn btn-info small-btn" title="View book pages" href="'.$link.'"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></span>',
             "permission"=>'<span class="f-left margin-r-5"> &nbsp;<a class="tble_button_st tooltips" data-toggle="tooltip" data-placement="top" title="Set Permission" href="'.$link.'"><i class="fa fa-cog" aria-hidden="true"></i></a></span>',
             "restore"=>'<span class="margin-r-5"><a id="restore_'.$class.'"  data-toggle="tooltip" data-placement="top" class="warning tooltips" title="Restore" rel="Restore" name="'.$link.'" href="javascript:;" Onclick="return ConfirmDeleteLovi(this.id,this.rel,this.name);"><i class="fa fa-database" aria-hidden="true"></i></a></span>',
             "addon"=>" &nbsp; <a href='".$link."' title='Addon' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-plus'></i> </a>"
            ];

        if(isset($array[$key]))
        {
            return $array[$key];
        }
        return '';
    }
}

   /*
** Array In check key exist or not 
*/
if ( ! function_exists('keyExist'))
{
    function keyExist($array=array(), $key)
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }
        else 
        {
            return '';
        }            
    }
}

if ( ! function_exists('getStatusAI'))
{
    function getStatusAI($status)
    {
        $getStatusArray = getStatusArray();
        if(isset($getStatusArray[$status]))
        {
            return $getStatusArray[$status];
        }
        return '';
    }
}

/*
** Get Status Array
*/
if (! function_exists('getStatusArray')) {

    function getStatusArray() {
        $return = ['1'=>'Active' , '0'=>'Inactive'];        
        return $return;
    }
}

if ( ! function_exists('upload'))
{
    /**
     * Access the upload helper
     */
    function upload($fileName,$path)
    {
          $file = $fileName;
          $destinationPath = $path; 
          $extension = $file->getClientOriginalExtension();
          $fileName = time().'.'.$extension;
          $file->move($destinationPath, $fileName);
          return $fileName;
    }
}

if ( ! function_exists('uploadWithResize'))
{
    /**
     * Access the uploadWithResize helper
     */
    function uploadWithResize($fileName,$path,$height=271,$width=287)
    {
        $image = $fileName;
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = $path;
        $destinationThumbPath = $path.'thumbnail';
        if(!File::isDirectory($destinationThumbPath)){
            File::makeDirectory($destinationThumbPath, 0777, true, true);
        }
        $img = \Image::make($image->getRealPath());
        $img->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        })->save($destinationThumbPath.'/'.$imageName);
        $image->move($destinationPath, $imageName);
        return $imageName;
    }
}

if ( ! function_exists('requestErrorApiResponse'))
{
    /**
     * requestErrorApiResponse
     */
    function requestErrorApiResponse($request)
    {
        $erro['status_code'] = 422;
        foreach($request as $error) {
           $erro['message'] = $error[0];
        }
        return $erro;
    }
}

if (!function_exists('changeErrorForAppResponse')) {
    /**
     * return change Error For App Response.
     *
     * @return array
     */
      function changeErrorForAppResponse($errors)
     {
          return $errors->first(); //for first && single error
          //return implode('', $errors); //for all && multiple error in single message
     }
}

//Get Configuration value by key
if (! function_exists('getConfig')) {
  function getConfig($slug)
  {
    $config = Configuration::where('slug','=',$slug)->first();
    if(!empty($config)){
      return $config->config_value;
    }else{
      return '';
    }
  }
}

//check Version Status
if (!function_exists('checkVersionStatus')) {
    function checkVersionStatus($device_type, $reqVersion) {
        $result = [];
        if ($device_type && $reqVersion) {
            $deviceVersion = getConfig(strtolower($device_type) . '-version');
            $force_update = getConfig(strtolower($device_type) . '-force-update');
            $result['forceUpdate'] = (int) $force_update;
            $result['updateAvailable'] = 0;
            $result['facebook_url'] = getConfig('facebook');;
            if ($deviceVersion > $reqVersion) {
                $result['updateAvailable'] = 1;
            }
            return $result;
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Platform and version are required.', []);
        }
    }
}

if (!function_exists('paginationFormat')) {
    /**
     * return data according paginate.
     *
     * @return array
     */
      function paginationFormat($request)
     { 
          $res['lastPage'] = $request->lastPage();
          $res['total'] = $request->total();
          $res['nextPageUrl'] = ($request->nextPageUrl()) ? $request->nextPageUrl() : "";
          $res['prevPageUrl'] = ($request->previousPageUrl()) ? $request->previousPageUrl() : "";
          $res['currentPage'] = $request->currentPage();
          return $res;
     }
}

if ( ! function_exists('savePushNotificationRecord'))
{
    /**
     * inset in to user notification push
     */
    function savePushNotificationRecord($userId,$title,$body)
    {
      return PushNotifications::create(['userId'=>$userId,'title'=>$title,'body'=>$body]);
    }
}

if ( ! function_exists('getLogoUrl'))
{
    /**
     * getLogoUrl
     */
    function getLogoUrl()
    {
      return Storage::disk('s3')->url('images/logo/logo.png');
    }
}

if ( ! function_exists('fixPriceFormate'))
{
    /**
     * fixPriceFormate
     */
    function fixPriceFormate($price)
    {
        $pr_arr = explode('.',$price);
        if(isset($pr_arr[1])){
            if($pr_arr[1] < 10){
              if(strlen($pr_arr[1]) ==1){
                $price = $pr_arr[0].'.'.$pr_arr[1].'0';
              }
            }
        }
        return $price;
    }
}

if ( ! function_exists('utctodtc_4now'))
{
    /**
     * utctodtc_4now
     */
    function utctodtc_4now()
    {
       return now()->subHours(4);
    }
}

