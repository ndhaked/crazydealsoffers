<?php

namespace Modules\StaticPages\Repositories;

use Modules\StaticPages\Entities\StaticPages;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class StaticPagesRepository implements StaticPagesRepositoryInterface {

    public $StaticPages;
    protected $model = 'StaticPages';

    function __construct(StaticPages $StaticPages) {
        $this->StaticPages = $StaticPages;
    }

    public function getRecord($id)
    {
      return $this->StaticPages->find($id);
    }

    public function getRecordBySlug($slug)
    {
      return $this->StaticPages->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
           DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();
            return DataTables::of($lists)
                    ->addColumn('action', function($list) use($model){
                        $dispalyButton = displayButton(['edit'=>[strtolower($model).'.edit', [$list->slug]],]);
                        $edit = keyExist($dispalyButton, 'edit');
                        return $edit;
                    })
                ->editColumn('name_en', function($list){
                    return \Illuminate\Support\Str::limit($list->name_en, 100, '...');
                })
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                })
                ->rawColumns(['action'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','name_en','description_en','meta_keyword_en','meta_description_en','banner');
            $filleable['banner_image'] = NULL;
            if($request->get('banner')){
                $filleable['banner_image'] = $request->get('banner_image');
            }
            $this->StaticPages->fill($filleable);
            $this->StaticPages->save();
            $response['message'] = trans('flash.success.static_page_created_successfully');
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
            $filleable = $request->only('slug','name_en','description_en','meta_keyword_en','meta_description_en');
            $record = $this->getRecord($id);
            $filleable['banner_image'] = NULL;
            $filleable['banner'] = NULL;
            if($request->get('banner')){
                $filleable['banner'] = 1;
                $filleable['banner_image'] = $request->get('banner_image');
            }
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.static_page_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->StaticPages->destroy($id);
    }

    public function saveBannerImageMedia($request)
    {
        if($request->hasfile('files'))
        {
            $file = $request->file('files');
            $filename=time().'.'.$file->getClientOriginalExtension();
            $filePath = 'images/staticpages/' . $filename;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            
            $response['status'] = true;
            $response['filename'] = $filename;
            return $response;
        }
    }
}
