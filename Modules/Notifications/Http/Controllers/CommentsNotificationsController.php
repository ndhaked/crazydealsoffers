<?php

namespace Modules\Notifications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DB,View,Session,Redirect;
use Modules\Notifications\Http\Requests\AddCommentsRequest;
use Modules\Notifications\Http\Requests\AddCommentsReplyRequest;
use Modules\Notifications\Repositories\Comments\CommentsNotificationsRepositoryInterface as CommentsNotificationsRepo;

class CommentsNotificationsController extends Controller
{
    public function __construct(CommentsNotificationsRepo $CommentsNotificationsRepo)
    {
        $this->middleware(['auth','ability'])->except(['getUsersListForTag','getProductsCommnetsForFront']);
        $this->CommentsNotificationsRepo = $CommentsNotificationsRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $notifications = $this->CommentsNotificationsRepo->getAllCommentNotifications($request);
        return view('notifications::comments.index',compact('notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function productComments(Request $request,$slug)
    {
        $product =  $this->CommentsNotificationsRepo->getProductBySlug($slug);
        if($product){
          $product = $this->CommentsNotificationsRepo->getProductComments($request,$slug);
          $tagUsers = json_encode($this->CommentsNotificationsRepo->getTagUserLists($request));
          return view('notifications::comments.comment_list',compact('product','tagUsers'));  
        }
        Session::flash('error', trans('Product not found'));
        return redirect()->route('commentnotifications.index');
    }

    public function addComments(AddCommentsRequest $request)
    {
        $response = $this->CommentsNotificationsRepo->addComments($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->back();
    }

    public function addCommentReply(AddCommentsReplyRequest $request)
    {
        $response = $this->CommentsNotificationsRepo->addCommentReply($request);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $data =  $this->CommentsNotificationsRepo->getCommentById($id);
            if($data){
                $this->CommentsNotificationsRepo->destroy($id);
                Session::flash('success', trans('Comment deleted successfully'));
                return redirect()->back();
            }
            Session::flash('error', trans('Comment not found'));
            return redirect()->back();
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_reocrd_try_later'));
            return redirect()->back();
        }
    }

    public function getUsersListForTag(Request $request)
    {
        $response = $this->CommentsNotificationsRepo->getTagUserLists($request);
        if($request->ajax()){
            return $response;
        }
    }

    public function getProductsCommnetsForFront(Request $request,$slug)
    {
        $response = $this->CommentsNotificationsRepo->getProductsCommnetsForFront($request,$slug);
        if($request->ajax()){
            return $response;
        }
    }
}
