<?php

namespace Modules\EmailTemplates\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB,View,Session,Redirect;
use Modules\EmailTemplates\Http\Requests\EmailTemplateRequest;
use Modules\EmailTemplates\Http\Requests\UpdateEmailTemplateRequest;
use Modules\EmailTemplates\Repositories\EmailTemplatesRepositoryInterface as EmailTemplateRepo;

class EmailTemplatesController extends Controller
{

     public function __construct(EmailTemplateRepo $EmailTemplateRepo)
    {
        $this->middleware(['auth','ability'])->except('getAjaxData');
        $this->EmailTemplateRepo = $EmailTemplateRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('emailtemplates::index');
    }

      /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->EmailTemplateRepo->getAjaxData($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('emailtemplates::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(EmailTemplateRequest $request)
    {
        return $this->EmailTemplateRepo->store($request);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$slug)
    {
        $data =  $this->EmailTemplateRepo->edit($request,$slug);
        if($data){
          return view('emailtemplates::edit',compact('data'));  
        }
        Session::flash('error', trans('flash.error.email_template_not_in_records'));
        return redirect()->route('email-templates.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateEmailTemplateRequest $request, $id)
    {
        $data =  $this->EmailTemplateRepo->getRecord($id);
        if($data){
            $this->EmailTemplateRepo->update($request,$id);
            return redirect()->route('email-templates.index');
        }
        Session::flash('error', trans('flash.error.email_template_not_in_records'));
        return redirect()->route('email-templates.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->EmailTemplateRepo->getRecord($id);
            if($data){
                $this->EmailTemplateRepo->destroy($id);
                Session::flash('success', trans('flash.success.email_template_deleted_successfully'));
                return redirect()->route('email-templates.index');
            }
            Session::flash('error', trans('flash.error.email_template_not_in_records'));
            return redirect()->route('email-templates.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('email-templates.index');
        }
    }
}
