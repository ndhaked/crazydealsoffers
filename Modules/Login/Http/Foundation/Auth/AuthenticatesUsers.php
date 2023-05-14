<?php

namespace Modules\Login\Http\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RedirectsUsers;


trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if($user->status==0){
            $this->guard()->logout();
            $msg = trans('flash.error.your_account_is_deactivated_contact_to_support');
            if($request->ajax()){
                   return  new JsonResponse(['status_code'=>500,'message' => $msg,'type'=>'success','reset'=>'true','url'=>$redirectTo], 200);
             }else{
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'email' => [$msg]
                ]);
                throw $error; 
             }
        }

        if($this->guard()->user()->hasRole(['admin','subadmin'])){
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->flash('error', trans('flash.error.this_is_user_login_area_you_cant_login'));
            $msg = trans('flash.error.this_is_user_login_area_you_cant_login');
            if($request->ajax()){
                   return  new JsonResponse(['status_code'=>500,'message' => $msg,'type'=>'success','reset'=>'true','url'=>$redirectTo], 200);
             }else{
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'email' => [$msg]
                ]);
                throw $error; 
             }
        }
        
        /*if(!$user->is_mobileVerifired()){
            $redirectTo = $this->redirectPath();
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $redirectTo = route(RouteServiceProvider::OTPVERIFICATION_ROUTE,$user->mobileVerifired->slug);
            $msg = trans('flash.error.please_complete_mobile_verification');
            return $request->wantsJson()
                    ? new JsonResponse(['status_code'=>200,'message' => $msg,'type'=>'success','reset'=>'true','url'=>$redirectTo], 200)
                    : redirect($redirectTo)->with('status', $msg);
        }*/
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $redirectPath = RouteServiceProvider::LOGIN;
        if (Auth::guard(get_guard())->user() && count(Auth::guard(get_guard())->user()->roles)>0) { 
            if (Auth::guard(get_guard())->user()->hasRole(['admin','subadmin'])) {
                $redirectPath = RouteServiceProvider::ADMIN_LOGIN;
            }
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('success', trans('flash.success.your_account_logged_out_successfully'));
        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($redirectPath);
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
