<?php

namespace Modules\Users\Repositories;

use App\Models\User;
use DB,Mail,Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Roles\Entities\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Illuminate\Support\Facades\Storage;

class UsersRepository implements UsersRepositoryInterface {

    public $User;

    function __construct(User $User,Role $Role) {
        $this->User = $User;
        $this->Role = $Role;
    }

    public function getRecord($id)
    {
      return $this->User->find($id);
    } 

    public function getRecordBySlug($slug)
    {
      return $this->User->where('slug',$slug)->first();
    }

    public function getUsersRoleList()
    {
        return $this->Role->getUsersRolePluckList(); 
    }

    public function getAll($request,$role)
    {
        $users = $this->User->orderBy('name','ASC')->whereHas('roles', function(Builder $q) use($role) {
                    if($role){
                        $q->where('slug',$role);
                    }
                });
        if($request->get('name')) {
            $users->where(function($query) use ($request) {
                $query->orWhere('name','LIKE', "%".$request->get('name')."%")
                        ->orWhere('name','LIKE', "%".$request->get('name')."%");
            });
        }
        if($request->get('email')) {
            $users->where('email',$request->get('email'));
        } 
        return $users->sortable('id')->paginate(30);
    }

    public function changeStatus($request,$slug)
    {
        $user = $this->getRecordBySlug($slug);
        if($user){
            $id = $user->id;
            $change = $this->User->find($id);
            $active = $change->status;
            if ($id != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                    $this->User->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->User->where('id', $id)
                        ->update($update_arr);
                }
                 $message = trans('flash.success.user_status_updated_successfully');
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

    public function saveProfilePictureMedia($request)
    {
        $file = $request->file('files');
        $filename=time().$file->getClientOriginalName();
        $filePath = 'images/user/' . $filename;
        if(\config::get('custom.image-upload-on')=='s3'){
                Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
                $responsePath = \Storage::disk('s3')->url('images/user/' . $filename);
        }else{
            $filePath = storage_path().'/app/public/user/';
            $filename = uploadWithResize($file,$filePath);
            $responsePath = \URL::to('storage/user/'.$filename);
        }
        if ($request->get('user_id')) {
            $user = $this->User->find($request->get('user_id'));
            $oldFilename = $user->FileExistsPath;
            $oldFilenameThumb = $user->FileExistsThumbPath;
            $oldName = $user->image;
            $user->image = $filename;
            if ($user->save()) {
                /*if($oldName != 'noimage.jpg') {
                    if (\File::exists($oldFilename)) {
                        \File::delete($oldFilename);
                    }
                    if (\File::exists($oldFilenameThumb)) {
                        \File::delete($oldFilenameThumb);
                    }
                }*/
            }
        }
        $response['status_code'] = 250;
        $response['status'] = true;
        $response['filename'] = $filename;
        $response['s3FullPath'] = $responsePath;
        return $response;
    }

    public function updateUserPassword($request)
    {
        $this->resetPassword($this->getRecordBySlug($request->slug), $request->password);
        $response['message'] = trans('flash.success.password_has_been_changed');
        $response['type'] = 'success';
        $response['status_code'] = 200;
        $response['reset'] = 'true';
        return $response;
    }

    protected function resetPassword($user, $password) {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }
}
