<?php

namespace Modules\Contactus\Repositories;

use Modules\Contactus\Entities\Contactus;
use Modules\StaticPages\Entities\StaticPages;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Modules\Configuration\Entities\Configuration;
use DB,Mail,Session;
use DataTables;
use Illuminate\Support\Facades\Input;

class ContactusRepository implements ContactusRepositoryInterface {

    public $Contactus;
    protected $model = 'Contactus';

    function __construct(Contactus $Contactus,StaticPages $StaticPages,EmailTemplate $EmailTemplate,Configuration $Configuration) {
        $this->Contactus     = $Contactus;
        $this->StaticPages   = $StaticPages;
        $this->EmailTemplate = $EmailTemplate;
        $this->Configuration = $Configuration;
    }

    public function getRecord($id)
    {
      return $this->Contactus->find($id);
    }

    public function getRecordBySlug($slug)
    {
      return $this->Contactus->findBySlug($slug);
    }
    public function getAjaxData($request)
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->latest()->get();   
            return DataTables::of($lists)
                ->addIndexColumn()
                ->addColumn('action', function($list) use($model) {
                            $dispalyButton = 
                            displayButton(
                            ['deleteAjax' => [strtolower($model) . '.destroy', [$list->slug]]]);
                            $delete = keyExist($dispalyButton, 'deleteAjax');
                                 return $delete;
                        }) 
                ->editColumn('email', function($list) {
                        return '<a href="mailto:'.$list->email.'" >'.$list->email.'</a>';
                    }) 
                ->editColumn('message', function($list) {
                    return $list->message;
                })
                ->editColumn('created_at', function($list){
                    return $list->created_at->format(\Config::get('custom.default_date_formate'));
                })
                ->rawColumns(['action','email'])
                ->make(true);
        } 
        catch (Exception $ex) {
             return false;
      }
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','name','email','message');
            $contact = $this->Contactus->create($filleable);
            $this->sendContactUsEmail($contact);
            $response['reset'] = 'true';
            $response['gcp-reset'] = 'true';
            $response['status_code'] = 200;
            $response['message'] = trans('flash.success.request_submitted_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }   
         return $response;  
    }

    public function destroy($id)
    {
      return $this->Contactus->destroy($id);
    }

    public function getStaticPageBySlug($slug)
    {
      return $this->StaticPages->findBySlug($slug);
    }

    /*
     * Send Email for Admin
    **/
    public function sendContactUsEmail($contact)
    {
        $emailtemplate = $this->EmailTemplate->where('slug', 'contact-us-request')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $search_fields = ['[username]','[name]','[email]','[message]'];
        $replace_data = ['Admin',$contact->name,$contact->email,$contact->message];
        $body = str_replace($search_fields, $replace_data, $body);
        $contactEmail = $this->getConfigValue('admin-email');
        $jobData = [
            'content' => $body,
            'to' => $contactEmail,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    protected function getConfigValue($slug) {
        $config = $this->Configuration->findBySlug($slug);
        if($config) 
            return $config->config_value;
        else
            return '';
    }   
}
