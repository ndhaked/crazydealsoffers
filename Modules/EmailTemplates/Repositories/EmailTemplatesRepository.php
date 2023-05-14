<?php

namespace Modules\EmailTemplates\Repositories;

use Modules\EmailTemplates\Entities\EmailTemplate;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class EmailTemplatesRepository implements EmailTemplatesRepositoryInterface {

    public $EmailTemplate;

    function __construct(EmailTemplate $EmailTemplate) {
        $this->EmailTemplate = $EmailTemplate;
    }

    public function getRecord($id)
    {
      return $this->EmailTemplate->find($id);
    }

    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $lists = $this->EmailTemplate->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->orderBy('id','desc')->get();  
            return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['email-templates.destroy', [$list->id]], 'edit'=>['email-templates.edit', [$list->slug]],]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    return $edit;
                })  
                ->editColumn('body', function($list){
                    return \Illuminate\Support\Str::limit($list->body, 600, '...'); 
                })
                ->editColumn('created_at', function($list){
                    return date_format($list->created_at,"m/d/Y");
                })
                ->rawColumns(['action','body'])
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','name','subject','body');
            $this->EmailTemplate->fill($filleable);
            $this->EmailTemplate->save();
            Session::flash('success', trans('flash.success.email_template_created_successfully'));
            return redirect()->route('email-templates.index');
        }catch (Exception $ex) {
            Session::flash('error', trans('flash.error.email_template_not_created_successfully'));
            return redirect()->route('email-templates.index');
        }        
    }

    public function edit($request,$slug)
    {
        return $this->EmailTemplate->findBySlug($slug);
    } 

    public function update($request,$id)
    {
     try {
        $filleable = $request->only('name','subject','body');
        $email = $this->EmailTemplate->find($id);
        $email->fill($filleable);
        $email->save();
            Session::flash('success', trans('flash.success.email_template_updated_successfully'));
        }catch (Exception $ex) {
            Session::flash('error', trans('flash.error.email_template_not_created_successfully'));
        } 
    }

    public function destroy($id)
    {
      return $this->EmailTemplate->destroy($id);
    }
}