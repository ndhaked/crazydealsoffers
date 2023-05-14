<?php

namespace Modules\Faq\Repositories;

use Modules\Faq\Entities\Faq;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class FaqRepository implements FaqRepositoryInterface {

    public $Faq;
    protected $model = 'Faq';

    function __construct(Faq $Faq) {
        $this->Faq = $Faq;
    }

    public function getRecord($id)
    {
      return $this->Faq->find($id);
    }

    public function getRecordBySlug($slug)
    {
      return $this->Faq->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
           DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();
            return DataTables::of($lists)
               ->addColumn('action', function($list) use($model){
                        $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]],]);
                        $edit = $delete = '';
                        if(auth()->user()->checkPermissionTo(strtolower($model).'.edit','admin'))
                        $edit = keyExist($dispalyButton, 'edit');
                        if(auth()->user()->checkPermissionTo(strtolower($model).'.destroy','admin'))
                        $delete = keyExist($dispalyButton, 'deleteAjax');
                        return $edit.$delete;
                    })  
                    ->editColumn('question', function($list){
                        return \Illuminate\Support\Str::limit($list->question, 150, '...');
                    }) 
                    ->editColumn('answer', function($list){
                       return \Illuminate\Support\Str::limit($list->answer, 150, '...');
                    })
                    ->rawColumns(['action','options'])
                    ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','faq_category_id','question','answer');
            $this->Faq->fill($filleable);
            $this->Faq->save();
            $response['message'] = trans('flash.success.faq_created_successfully');
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
            $filleable = $request->only('slug','faq_category_id','question','answer');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.faq_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->Faq->destroy($id);
    }
}
