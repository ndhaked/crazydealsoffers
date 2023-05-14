<?php

namespace Modules\AdvertiseAffiliated\Repositories;

use Modules\AdvertiseAffiliated\Entities\AdvertiseAffiliated;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Modules\AdvertiseAffiliated\Repositories\AdvertiseAffiliatedRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class AdvertiseAffiliatedRepository implements AdvertiseAffiliatedRepositoryInterface {

    public $AdvertiseAffiliated;
    protected $model = 'AdvertiseAffiliated';

    function __construct(AdvertiseAffiliated $AdvertiseAffiliated) {
        $this->AdvertiseAffiliated = $AdvertiseAffiliated;
    }

    public function getAll($request)
    {
        return $this->AdvertiseAffiliated->sortable('id')->paginate(10);
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $lists = $this->AdvertiseAffiliated->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->orderBy('id','desc')->get();  
            return DataTables::of($lists)
                    ->addColumn('action', function($list){
                    // $dispalyButton = displayButton(['delete'=>['advertiseaffiliated.destroy', [$list->id]], 'edit'=>['advertiseaffiliated.edit', [$list->id]],getStatusAI($list->status)=>['advertiseaffiliated.status',[$list->id]],]);
                    $dispalyButton = displayButton(['delete'=>['advertiseaffiliated.destroy', [$list->id]], 'edit'=>['advertiseaffiliated.edit', [$list->id]],]);
                    $status = $edit = $delete = '';
                    // $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    
                    return $status.$edit;
                })  
               ->editColumn('page', function($list){
                    return \Illuminate\Support\Str::limit($list->page, 100, '') .' (Order '.$list->ad_order.')';
                })
                ->editColumn('banner_image', function($list){
                    return '<a class="" href="'.$list->S3Url.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3Url.'"></a>';
                })
                ->editColumn('image_1', function($list){
                    return '<a class="" href="'.$list->S3UrlImage2.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3UrlImage2.'"></a>';
                })
                ->editColumn('image_2', function($list){
                    return '<a class="" href="'.$list->S3UrlImage3.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3UrlImage3.'"></a>';
                })
                ->editColumn('description', function($list){
                    return '<span>'.substr($list->description,0,50).'</span>';
                })
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                }) 
                // ->editColumn('status', function($list){
                //     return ($list->status==1) ? '<span class="label label-success">'.'Active'.'</span>' : '<span class="label label-danger">'.'Inactive'.'</span>';
                // })
                ->rawColumns(['banner_image','image_1','image_2','description','status','action'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function saveProductPictureMedia($request)
    {
         if($request->hasfile('files'))
        {
            $file = $request->file('files');
            $filename=time().$file->getClientOriginalName();
            $filePath = 'images/advertise_affiliated/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }

    public function changeStatus($request,$id)
    {
            $change = $this->AdvertiseAffiliated->find($id);
            $active = $change->status;
            if ($change != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                    $this->AdvertiseAffiliated->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->AdvertiseAffiliated->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.advertisement_status_updated_successfully');
                 $type = 'success';
            }else{
                 $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                 $type = 'warning';
            }
        
         $response['status_code'] = 200;
         $response['message'] = $message;
         $response['type'] = $type;
         return $response;
    }

    public function getRecordBySlug($slug)
    {
      return $this->AdvertiseAffiliated->where('slug',$slug)->first();
    }

    public function getRecord($slug)
    {
      return $this->AdvertiseAffiliated->where('slug',$slug)->first();
    }
    
    public function update($request,$slug)
    {
        try {
            $filleable = $request->only('slug','title','banner_image','banner_description','image_1','description_1','image_2','description_2','description');
            $record = $this->getRecord($slug);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.advertisaffiliated_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }
}
