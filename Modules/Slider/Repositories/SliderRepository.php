<?php

namespace Modules\Slider\Repositories;

use Modules\Slider\Entities\Slider;
use Modules\Configuration\Entities\Configuration;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Modules\Slider\Repositories\SliderRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class SliderRepository implements SliderRepositoryInterface {

    protected $model = 'Slider';

    function __construct(Slider $Slider) {
        $this->Slider = $Slider;
    }

    public function getRecord($id)
    {
      return $this->Slider->find($id);
    }
    
    public function getSliderCount()
    {
      return $this->Slider->count();
    }
    
    public function getSliderOrder()
    {
      $order =  $this->Slider->pluck('slider_order');
      $limit  = array('1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5);
      $counter = array_diff($limit,$order->toArray());
      return $counter;
    }
    
    public function getEditSliderOrder($num)
    {
      $order =  $this->Slider->where('slider_order','!=',$num)->pluck('slider_order');
      $limit  = array('1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5);
      $counter = array_diff($limit,$order->toArray());
      return $counter;
    }

    public function getRecordBySlug($slug)
    {
      return $this->Slider->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
           DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
             $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();
            return DataTables::of($lists)
               ->addColumn('action', function($list) use($model){
                    $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]],getStatusAI($list->status)=>[strtolower($model).'.status',[$list->slug]],]);
                    $status = $edit = $delete = '';
                    $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    return $status.$edit.$delete;
                })

                ->editColumn('name', function($list){
                    return \Illuminate\Support\Str::limit($list->title, 100, '');
                })
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                }) 
                ->editColumn('slider_order', function($list){
                    return $list->slider_order;
                }) 
                ->editColumn('status', function($list){
                    return ($list->status==1) ? '<span class="label label-success">'.'Active'.'</span>' : '<span class="label label-danger">'.'Inactive'.'</span>';
                })
                ->editColumn('banner_image', function($list){
                    return '<a class="" href="'.$list->S3Url.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3Url.'"></a>';
                })
                ->rawColumns(['status','action','banner_image','slider_order'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','banner_image','title','url','description','slider_order');
            if ($request->get('banner_image')) $filleable['banner_image'] = $request->get('banner_image');
            $this->Slider->fill($filleable);
            $this->Slider->save();
            $response['message'] = trans('flash.success.slider_created_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }   
         return $response;  
    }

    public function update($request,$id)
    {
        try {
            $filleable = $request->only( 'slug','banner_image','title','url','description','status','slider_order');
            if ($request->get('banner_image')) $filleable['banner_image'] = $request->get('banner_image');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.slider_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
       $record = $this->Slider->find($id);
       if($record){
            return $this->Slider->destroy($id);
       }
       return false;
    }

    public function changeStatus($request,$slug)
    {
        $Slider = $this->getRecordBySlug($slug);
        if($Slider){
            $id = $Slider->id;
            $change = $this->Slider->find($id);
            $active = $change->status;
            if ($id != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                    $this->Slider->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->Slider->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.slider_status_updated_successfully');
                 $type = 'success';
            }else{
                 $message =  trans('flash.error.oops_something_went_wrong_updating_record');
                 $type = 'warning';
            }
        }else{
             $message =  trans('flash.error.oops_something_went_wrong_updating_record');
             $type = 'warning';
        }
         $response['status_code'] = 200;
         $response['message'] = $message;
         $response['type'] = $type;
         return $response;
    }

    public function saveSliderPictureMedia($request)
    {        
        if($request->hasfile('files'))
        {
            $file = $request->file('files');
            $filename=time().$file->getClientOriginalName();
            $filePath = 'images/slider/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }

    public function sliderShowHide($status)
    {
        Configuration::where('slug','slider')->update(['config_value'=>$status]);
        return true;
    }
}
