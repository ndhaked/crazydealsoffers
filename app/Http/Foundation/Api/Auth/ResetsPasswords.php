<?php

namespace App\Http\Foundation\Api\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

trait ResetsPasswords
{
    use RedirectsUsers;

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request['password_confirmation'] = $request->get('password');
        $payload = app('request')->only('email','password','token','password_confirmation');
        $validator = app('validator')->make($request->all(), $this->rules());

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException( changeErrorForAppResponse($validator->errors()) , $validator->errors());
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
       /* $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );*/
         $user = $this->User->where('email',$request->get('email'))->first();
         if($user){
            if($user->remember_token == $request->get('token')){
                $this->resetPassword($user, $request->get('password'));
                $response = 'passwords.reset';
            }else{
                $response = 'passwords.token';
            }
         }else{
            $response = 'passwords.user';
         }
        
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request,$response)
                    : $this->sendResetFailedResponse($request, $response);
    }

 
     /**
     * Get the Login validation rules.
     *
     * @return array
     */
    protected function rules() {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'token' => 'required',
        ];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password','token','password_confirmation'
        );
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

       // $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($request,$response)
    {
        return response()->json(['status_code'=> 200,'message' => trans($response)], 200)->withHeaders(checkVersionStatus($request->headers->get('Platform'),$request->headers->get('Version'))); 
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
         return response()->json(['status_code'=> 400,'message' => trans($response)], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'),$request->headers->get('Version')));  
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
