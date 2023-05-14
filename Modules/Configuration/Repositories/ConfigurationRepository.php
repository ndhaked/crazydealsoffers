<?php

namespace Modules\Configuration\Repositories;

use Modules\Configuration\Entities\Configuration;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class ConfigurationRepository implements ConfigurationRepositoryInterface {

    public $Configuration;
    protected $model = 'Configuration';

    function __construct(Configuration $Configuration) {
        $this->Configuration = $Configuration;
    }

    public function getRecord($id)
    {
      return $this->Configuration->find($id);
    }

    public function getRecordBySlug($slug)
    {
      return $this->Configuration->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->where('slug','!=','slider')->get();  
            return DataTables::of($lists)
                    ->addColumn('action', function($list) use($model){
                        $dispalyButton = displayButton([/*'delete'=>[strtolower($model).'.destroy', [$list->slug]],*/ 'edit'=>[strtolower($model).'.edit', [$list->slug]],]);
                        $edit = keyExist($dispalyButton, 'edit');
                        $delete = keyExist($dispalyButton, 'delete');
                        return $edit;
                    })  
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                })
                 ->editColumn('config_value', function($list){
                     return $list->config_value;
                })
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }                
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','config_title','config_value');
            $this->Configuration->fill($filleable);
            $this->Configuration->save();
            $response['message'] = trans('flash.success.configuration_created_successfully');
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
            $filleable = $request->only('slug','config_title','config_value');
            $record = $this->getRecord($id);
            $record->fill($filleable);
            $record->save();
            $response['message'] = trans('flash.success.configuration_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->Configuration->destroy($id);
    }
}
