<?php

namespace App\Http\Foundation\Api\Auth;

use Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Modules\EmailTemplates\Entities\EmailTemplate;

trait ApiSendsPasswordResetEmails
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
            $payload = app('request')->only('email');
            $validator = app('validator')->make($payload, $this->rules());

            if ($validator->fails()) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException( changeErrorForAppResponse($validator->errors()) , $validator->errors());
            }
            
            $response = 'passwords.user';
            $user = User::where('email',$request->only('email'))->first();
            $forgotOtp = mt_rand(1000,9999);
            if(isset($user)){
                $token = Password::getRepository()->create($user);
                if($request->isotp==1){
                    if($user->remember_token == $request->otp){
                        $forgotOtp = $token;
                    }else{
                        return response()->json([
                            'status_code' => 400,
                            'message' => 'Invalid OTP',
                            'data' => []
                        ], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'),$request->headers->get('Version'))); 
                    }
                }else{
                    $emailtemplate = EmailTemplate::where('slug','forgot-password-otp-email')->first();
                    $subject = $emailtemplate->subject;
                    $body = $emailtemplate->body;
                    $body = str_replace('[username]', $user->name, $body);
                    $body = str_replace('[forgotOtp]', $forgotOtp, $body);
                    $jobData = [
                        'content' => $body,
                        'to' => $user->email,
                        'subject' => $subject
                    ];
                    dispatch(new \App\Jobs\SendEmailJob($jobData));
                }
                $response = 'passwords.sent'; 
                $this->changeresetLinkToken($user,$forgotOtp);
            } 
            return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response,$forgotOtp)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }


    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request,$response,$forgotOtp)
    {
        if($request->isotp==1){
            return response()->json(['status_code'=> 200,'token'=>$forgotOtp,'message' => 'OTP verify successfully!'], 200)->withHeaders(checkVersionStatus($request->headers->get('Platform'),$request->headers->get('Version')));
        }else{
         return response()->json(['status_code'=> 200,'otp'=>$forgotOtp,'message' => 'OTP sent successfully'], 200)->withHeaders(checkVersionStatus($request->headers->get('Platform'),$request->headers->get('Version')));
        }
    }

   
    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
           return response()->json(['status_code'=>400,'message' => trans($response)], 400)->withHeaders(checkVersionStatus($request->headers->get('Platform'),$request->headers->get('Version')));
    }

    public function changeresetLinkToken($user,$forgotOtp)
    {
          $user->forceFill([
                'remember_token' => $forgotOtp,
                //'remember_token' => $token,
            ])->save();
        return $user->remember_token;
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
}
