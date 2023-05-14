<?php

namespace Modules\Advertisements\Repositories;

use Modules\Advertisements\Entities\Advertisements;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Log;
use Modules\Advertisements\Repositories\AdvertisementsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class AdvertisementsRepository implements AdvertisementsRepositoryInterface {

    public $Advertisements;
    protected $model = 'Advertisements';

    function __construct(Advertisements $Advertisements) {
        $this->Advertisements = $Advertisements;
    }

    public function getAll($request)
    {
        return $this->Advertisements->sortable('id')->paginate(10);
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $lists = $this->Advertisements->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->orderBy('id','desc')->get();  
            return DataTables::of($lists)
                    ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['advertisement.destroy', [$list->id]], 'edit'=>['advertisement.edit', [$list->id]],getStatusAI($list->status)=>['advertisement.status',[$list->id]],]);
                    $status = $edit = $delete = '';
                    $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    
                    return $status.$edit;
                })  
               ->editColumn('page', function($list){
                    return \Illuminate\Support\Str::limit($list->page, 100, '') .' (Order '.$list->ad_order.')';
                })
                ->editColumn('image', function($list){
                    return '<a class="" href="'.$list->S3Url.'" data-lightbox="example-1"><img class="" style="width: 60px;" src="'.$list->S3Url.'"></a>';
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
            $filleable = $request->only('slug','page','image','advertisement_link');
            $this->Advertisements->fill($filleable);
            $this->Advertisements->save();
            $response['message'] = trans('flash.success.advertisement_created_successfully');
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
            $filePath = 'images/advertisements/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }

    public function changeStatus($request,$id)
    {
            $change = $this->Advertisements->find($id);
            $active = $change->status;
            if ($change != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                    $this->Advertisements->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->Advertisements->where('id', $id)
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

    public function getRecordBySlug($id)
    {
      return $this->Advertisements->find($id);
    }

    public function getRecord($id)
    {
      return $this->Advertisements->find($id);
    }
    
    public function update($request,$id)
    {
        try {
            $filleable = $request->only('slug','page','image','advertisement_link');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.advertisement_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->Advertisements->destroy($id);
    }
}
