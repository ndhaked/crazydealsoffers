<?php

namespace Modules\SocialLogin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\SocialLogin\Repositories\SocialLoginInterface as SocialLoginRepo;

class SocialLoginController extends Controller
{
   /*
    |--------------------------------------------------------------------------
    | SocialLoginController Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SocialLoginRepo $SocialLoginRepo)
    {
        $this->middleware('guest');
        $this->SocialLoginRepo = $SocialLoginRepo;
    }

     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return \Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information
     *
     * @return Response
     */
    public function handleProviderCallback($provider,Request $request)
    {
        return $this->SocialLoginRepo->handleProviderCallback($provider,$request);
    }
}
