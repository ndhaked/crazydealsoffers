<?php

namespace Modules\Users\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Users\Repositories\UsersRepositoryInterface as UsersRepo;
use Modules\Users\Http\Requests\ProfileMediaRequest;
use Modules\Users\Http\Requests\ChangePasswordRequest;

class UsersController extends Controller
{
    protected $role = 'customer';
    public function __construct(UsersRepo $UsersRepo)
    {
        $this->middleware(['ability','auth']);
        $this->UsersRepo = $UsersRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        $users = $this->UsersRepo->getAll($request,$this->role);
        // $countryPluck = $this->CommonRepo->getCountryPluck();
        return view('users::admin.user.index',compact('users'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($slug)
    {
        $user =  $this->UsersRepo->getRecordBySlug($slug);
        if($user){
          return view('users::admin.user.show',compact('user'));  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('users.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request,$slug)
    {   
        $response = $this->UsersRepo->changeStatus($request,$slug);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back();   
    }

     /**
     * Update the passowrd for requested user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    protected function storeChangeUserPassword(ChangePasswordRequest $request) {
        try {
            $response = $this->UsersRepo->updateUserPassword($request);
            if ($request->ajax()) {
                return response()->json($response);
            }
            $request->session()->flash('success', trans('flash.success.password_has_been_changed'));
            return back();
        } catch (\Exception $e) {
            return response()->json(['type'=>'error',"status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * upload user profile picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(ProfileMediaRequest $request) {
        try {
            $response = $this->UsersRepo->saveProfilePictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
