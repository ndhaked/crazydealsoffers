<?php

namespace Modules\Notifications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB,View,Session,Redirect;
use Modules\Notifications\Http\Requests\SendNotificationRequest;
use Modules\Notifications\Repositories\NotificationsRepositoryInterface as NotificationsRepo;

class NotificationsController extends Controller
{
    public function __construct(NotificationsRepo $NotificationsRepo)
    {
        $this->middleware(['auth','ability'])->except(['getAjaxData','getSuggessionDeals','getUsersLists']);
        $this->NotificationsRepo = $NotificationsRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->NotificationsRepo->getAll($request,'customer');
        return view('notifications::index',compact('users'));
    }

      /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    {
      return $this->NotificationsRepo->getAjaxData($request);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('notifications::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(SendNotificationRequest $request)
    {
        $response = $this->NotificationsRepo->store($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request,$id)
    {
        $data =  $this->NotificationsRepo->edit($request,$id);
        if($data){
            $allUsers = $this->NotificationsRepo->getAllUsersPluck();
          return view('notifications::edit',compact('data','allUsers'));  
        }
        Session::flash('error', trans('flash.error.notification_not_in_records'));
        return redirect()->route('notifications.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(SendNotificationRequest $request, $id)
    {
        $data =  $this->NotificationsRepo->getRecord($id);
        if($data){
            $this->NotificationsRepo->resendNotifications($request,$id);
            return redirect()->route('notifications.index');
        }
        Session::flash('error', trans('flash.error.email_template_not_in_records'));
        return redirect()->route('notifications.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->NotificationsRepo->getRecord($id);
            if($data){
                $this->NotificationsRepo->destroy($id);
                Session::flash('success', trans('flash.success.notifications_deleted_successfully'));
                return redirect()->route('notifications.index');
            }
            Session::flash('error', trans('flash.error.notifications_not_in_records'));
            return redirect()->route('notifications.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->route('notifications.index');
        }
    }

    public function getSuggessionDeals(Request $request)
    {
        $response = $this->NotificationsRepo->getSuggessionDeals($request);
        if($request->ajax()){
            return response()->json($response);
        }
    }

    public function getUsersLists(Request $request)
    {
        $response = $this->NotificationsRepo->getUsersLists($request);
        if($request->ajax()){
            return response()->json($response);
        }
    }
}
