<?php

namespace App\Http\Controllers\Api\UserProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\UserProfile\UserProfileRepositoryInterface as UserProfileRepo;

class UserController extends BaseController
{
    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct(UserProfileRepo $UserRepo,Request $request) {
        $this->UserRepo = $UserRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request) {
        $response = $this->UserRepo->getUserDetails($request);
        return $response;
    }

	/**
     * User Update.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userUpdate(Request $request) {
        $response = $this->UserRepo->userUpdate($request);
        return $response;
    }

    /**
     * User Change Password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userChangePassword(Request $request) {
        $response = $this->UserRepo->userChangePassword($request);
        return $response;
    }

    /**
     * Get the Signed URL User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signedURL(Request $request) {
        $response = $this->UserRepo->getSignedURL($request);
        return $response;
    }

     /**
     * Get the home page data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHomepageData(Request $request) {
        $response = $this->UserRepo->getHomepageData($request);
        return $response;
    }

    /**
     * Get the home page get Auto completeData data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAutocompleteData(Request $request) {
        $response = $this->UserRepo->getAutocompleteData($request);
        return $response;
    }

    /**
     * update notification status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotificationStatus(Request $request) {
        $response = $this->UserRepo->updateNotificationStatus($request);
        return $response;
    }

    /**
     * delete Account Permanently
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccountPermanently(Request $request) {
        $response = $this->UserRepo->deleteAccountPermanently($request);
        return $response;
    }
}