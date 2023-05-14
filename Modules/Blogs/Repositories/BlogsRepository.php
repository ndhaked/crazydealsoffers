<?php

namespace Modules\Blogs\Repositories;

use Modules\Blogs\Entities\Blogs;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Modules\Blogs\Repositories\BlogsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class BlogsRepository implements BlogsRepositoryInterface {

    public $Blogs;
    protected $model = 'Blogs';

    function __construct(Blogs $Blogs) {
        $this->Blogs = $Blogs;
    }

    public function getAll($request)
    {
        return $this->Blogs->sortable('id')->paginate(10);
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $lists = $this->Blogs->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->orderBy('id','desc')->get();  
            return DataTables::of($lists)
                    ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['blog.destroy', [$list->id]], 'edit'=>['blog.edit', [$list->id]],getStatusAI($list->status)=>['blog.status',[$list->id]],]);
                    $status = $edit = $delete = '';
                    $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    
                    return $status.$edit.$delete;
                })  
               ->editColumn('title', function($list){
                    return \Illuminate\Support\Str::limit($list->title, 100, '');
                })
                ->editColumn('image_1', function($list){
                    return '<a class="" href="'.$list->S3Url.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3Url.'"></a>';
                })
                ->editColumn('image_2', function($list){
                    return ($list->S3UrlImage2) ? '<a class="" href="'.$list->S3UrlImage2.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3UrlImage2.'"></a>' : 'N/A';
                })
                ->editColumn('created_at', function($list){
                    return $list->created_at->subHours(4)->format(\Config::get('custom.default_date_formate'));
                }) 
                ->editColumn('status', function($list){
                    return ($list->status==1) ? '<span class="label label-success">'.'Active'.'</span>' : '<span class="label label-danger">'.'Inactive'.'</span>';
                })
                ->rawColumns(['image_1','image_2','status','action'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','title','description','image_1','image_2', 'video_link');
            $this->Blogs->fill($filleable);
            $this->Blogs->save();
            $response['message'] = trans('flash.success.blog_created_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }   
         return $response;  
    }

    public function saveProductPictureMedia($request)
    {
         if($request->hasfile('files'))
        {
            $file = $request->file('files');
            $filename=time().$file->getClientOriginalName();
            $filePath = 'images/blogs/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }

    public function changeStatus($request,$id)
    {
            $change = $this->Blogs->find($id);
            $active = $change->status;
            if ($change != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                    $this->Blogs->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->Blogs->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.blog_status_updated_successfully');
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

    public function getRecordBySlug($id)
    {
      return $this->Blogs->find($id);
    }

    public function getRecord($id)
    {
      return $this->Blogs->find($id);
    }
    
    public function update($request,$id)
    {
        try {
            $filleable = $request->only('slug','title','description','image_1','image_2','video_link');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.blog_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }
    
    public function removeImage($id)
    {
        try {
            $record = $this->getRecord($id);
            $record->fill(['image_2'=>null]);
            $record->save();
            $response['message'] = trans('flash.success.blog_image_removed_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->Blogs->destroy($id);
    }
}
