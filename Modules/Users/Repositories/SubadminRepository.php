<?php

namespace Modules\Users\Repositories;

use App\Models\User;
use DB,Mail,Session;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailTemplates\Entities\EmailTemplate;

class SubadminRepository implements SubadminRepositoryInterface {

    public $User;
    public $rolename = 'subadmin';

    function __construct(User $User,Role $Role) {
        $this->User = $User;
        $this->Role = $Role;
    }

    public function getRecord($id)
    {
        return $this->User->where('id',$id)->whereHas('roles', function(Builder $q) {
                   $q->whereIn('slug',[$this->rolename,'admin']);
                })->first();
    } 

    public function getRecordBySlug($slug)
    {
      return $this->User->where('slug',$slug)->whereHas('roles', function(Builder $q) {
                if(auth()->user()->hasRole('admin')){
                    $q->where('slug','admin');
                }else{
                    $q->where('slug',$this->rolename);
                }
                })->first();
    }

     public function getRecordBySlugForAdminAndSubadmin($slug)
    {
      return $this->User->where('slug',$slug)->whereHas('roles', function(Builder $q) {
                    $q->whereIn('slug',[$this->rolename,'admin']);
                })->first();
    }

    public function store($request)
    {
        try {
            $filleable = $request->only('slug','username','name', 'email');
            $password  = $request['password'];
            $filleable['password'] = trim(Hash::make($request['password']));
            $filleable['email_verified_at'] = now();
            if ($request->get('image')) {
                $filleable['image'] = $request['image'];
            }
            if($user = $this->User->create($filleable)){
                $role = $this->Role->where('slug','subadmin')->first();
                if($role){
                     User::$guard_name = 'admin';
                    //Assign Sub Admin Role
                    $user->assignRole([$role->id]);
                    $this->sendWelcomeEmailForSubadmin($user,$password);
                }
            }
            $response['message'] = trans('flash.success.subadmin_created_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_creating_record');
            $response['type'] = 'error';
        }   
         return $response;     
    }

    public function edit($slug)
    {
        return $this->User->findBySlug($slug);
    } 

    public function update($request,$id)
    {
        try {
            $filleable = $request->only('name', 'email');
            if ($request->get('password')) {
                $filleable['password'] = trim(Hash::make($request['password']));
            }
            if ($request->get('image')) {
                $filleable['image'] = $request['image'];
            }
            $user = $this->User->find($id);
            $user->fill($filleable);
            $user->save();
            $response['message'] = trans('flash.success.subadmin_updated_successfully');
            $response['type'] = 'success';
        }catch (Exception $ex) {
            $response['message'] = trans('flash.error.oops_something_went_wrong_updating_record');
            $response['type'] = 'error';
        } 
        return $response;
    }

    public function destroy($id)
    {
      return $this->User->destroy($id);
    }

    public function sendWelcomeEmailForSubadmin($user,$password)
    {
        $emailtemplate = EmailTemplate::where('slug', 'create-user')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', $user->name, $body);
        $body = str_replace('[email]', $user->email, $body);
        $body = str_replace('[password]', $password, $body);
        $body = str_replace('[loginurl]', route('admin.login'), $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $user->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }
}
