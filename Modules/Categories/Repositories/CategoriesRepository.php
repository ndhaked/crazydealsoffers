<?php

namespace Modules\Categories\Repositories;

use Modules\Categories\Entities\Categories;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Illuminate\Support\Facades\Storage;

class CategoriesRepository implements CategoriesRepositoryInterface {

    protected $model = 'Categories';

    function __construct(Categories $Categories) {
        $this->Categories = $Categories;
    }

    public function getRecord($id)
    {
      return $this->Categories->find($id);
    }

    public function getRecordBySlug($slug)
    {
      return $this->Categories->findBySlug($slug);
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
                    if(auth()->user()->checkPermissionTo(strtolower($model).'.edit','admin'))
                        $edit = keyExist($dispalyButton, 'edit');
                    if(auth()->user()->checkPermissionTo(strtolower($model).'.destroy','admin'))
                        $delete = keyExist($dispalyButton, 'deleteAjax');
                    if($list->parent_id){
                        return $status.$edit.$delete; 
                    }
                    return $status.$edit;
                })

                ->editColumn('name', function($list){
                    return \Illuminate\Support\Str::limit($list->name, 100, '');
                })
                ->editColumn('image', function($list){
                    return '<a class="" href="'.$list->S3Url.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3Url.'"></a>';
                })
                ->editColumn('parent_category', function($list){
                    return ($list->parent) ? $list->parent->name : 'N/A';
                }) 
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                }) 
                ->editColumn('status', function($list){
                    return ($list->status==1) ? '<span class="label label-success">'.'Active'.'</span>' : '<span class="label label-danger">'.'Inactive'.'</span>';
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only( 'slug','name','description','image','parent_id');
            $this->Categories->fill($filleable);
            $this->Categories->save();
            $response['message'] = trans('flash.success.category_created_successfully');
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
            $filleable = $request->only( 'slug','name','description','status','image','parent_id');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.category_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
       $record = $this->Categories->find($id);
       if($record->parent_id){
            return $this->Categories->destroy($id);
       }
       return false;
    }

    public function changeStatus($request,$slug)
    {
        $Categories = $this->getRecordBySlug($slug);
        if($Categories){
            $id = $Categories->id;
            $change = $this->Categories->find($id);
            $active = $change->status;
            if ($id != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                    $this->Categories->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->Categories->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.category_status_updated_successfully');
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

    public function getAllParentCategories() {
        return  $this->Categories->where('parent_id', null)->pluck('name','id')->toArray();
    }

    public function saveCategoryPictureMedia($request)
    {
        if($request->hasfile('files'))
        {
            $file = $request->file('files');
            $filename=time().'.'.$file->getClientOriginalExtension();
            $filePath = 'images/category/' . $filename;
            if(\config::get('custom.image-upload-on')=='s3'){
                Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            }else{
                $filePath = 'images/category/';
                $filename = upload($file,$filePath);
            }
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }
}
