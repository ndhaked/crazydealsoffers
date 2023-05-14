<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Auth\AuthenticationRepositoryInterface as AuthenticationRepo;

class AuthenticationController extends BaseController
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthenticationRepo $AuthRepo) {
        $this->AuthRepo = $AuthRepo;
        $this->middleware('auth:api', ['except' => ['login', 'register','resendOtp','socailLogin','guestRegister']]);
        return auth()->shouldUse('api');
    }

    /**
     * Login a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    { 
        $response = $this->AuthRepo->login($request);
        return $response;
    }

    /**
     * socail Login a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function socailLogin(Request $request)
    { 
        $response = $this->AuthRepo->socailLogin($request);
        return $response;
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $response = $this->AuthRepo->register($request);
        return $response;
    }

     /**
     * Register a Guest User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function guestRegister(Request $request) {
        $response = $this->AuthRepo->guestRegister($request);
        return $response;
    }

    /**
     * resend Otp.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request) {
        $response = $this->AuthRepo->resendOtp($request);
        return $response;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        $response = $this->AuthRepo->logout($request);
        return $response;
    }
}