<?php

namespace App\Repositories\Auth;

use DB,Mail;
use config,File;
use Validator;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Builder;
use Modules\EmailTemplates\Entities\EmailTemplate;
use App\Models\User;
use Modules\Roles\Entities\Role;
use Modules\Login\Entities\MobileVerification;
use Modules\Login\Entities\OtpVerifications;
use Modules\SocialLogin\Entities\LinkedSocialAccount;
use App\Models\JwtUserTokens;
use Modules\Users\Entities\GuestUsers;

class AuthenticationRepository implements AuthenticationRepositoryInterface {

    function __construct(User $User,GuestUsers $GuestUsers) {
       $this->User = $User;
       $this->GuestUsers = $GuestUsers;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_uid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }

        if (User::where('email', $request->email)->exists()) {
            if (! $token =  auth()->attempt($request->only('email','password'))) {
                return response()->json(['status_code'=> 401,'message' => 'Password is wrong'], 401);
            }
            if (auth()->user()->status == 0) {
                return response()->json(['status_code'=> 401,'message' => 'Your account is deactivate please contact to Support'], 401);
            }
            $user = User::where('email', $request->email)->first();
            
            auth()->user()->forceFill(['device_token'=>$request->device_token])->save();
            if($request->device_uid){
                GuestUsers::where('device_uid',$request->device_uid)->update(['device_token' => NULL]);
            }
            $response =  $this->createNewTokenForLogin($token); 
           
            return $response;
        }else{
             return response()->json(['status_code'=> 401,'message' => 'User not exists with this email address'], 401);
        }
    }

    public function register($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required',
            'phone' => ['nullable'],
            'device_uid' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if($request->provider_id != '' && $request->provider_name != ''){
                $validator = Validator::make($request->all(), [
                    'is_email_edit' => 'required|in:1,0',
                ]);
                if($validator->fails()){
                    return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
                }
                if($request->is_email_edit==1 && $request->isotp==1){
                    $otpRecord = OtpVerifications::where('email',$request->email)->first();
                    if($otpRecord && $otpRecord->otp_verification_code == $request->otp){
                        $otpRecord->delete();
                        return $this->findOrCreateDirect($request);
                    }else{
                        return response()->json([
                            'status_code' => 400,
                            'message' => 'Invalid OTP',
                            'data' => []
                        ], 400); 
                    }
                }elseif($request->is_email_edit==1){
                    OtpVerifications::where('email',$request->email)->delete();
                    $otpCode =  mt_rand(1000,9999);
                    OtpVerifications::create([
                            'email'=> $request->email,
                            'otp_verification_code' => $otpCode
                    ]);
                    $this->sendOTPVerificationEmail($request,$request->password,$otpCode);
                    return response()->json([
                        'status_code' => 201,
                        'message' => 'OTP sent successfully',
                        'otp' => $otpCode,
                        'data' => []
                    ], 201);
                }else{
                    return $this->findOrCreateDirect($request);
                }
        }
        if($request->isotp==1){ 
            $otpRecord = OtpVerifications::where('email',$request->email)->first();
            if($otpRecord && $otpRecord->otp_verification_code == $request->otp){
                $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
                $role = Role::where('slug','customer')->first();
                $user->assignRole([$role->id]);
                $password =  $request->password;
                $otpRecord->delete();

                if($request->device_uid){
                    GuestUsers::where('device_uid',$request->device_uid)->update(['device_token' => NULL]);
                }
                $this->sendWelcomeEmailForUser($user,$password);
                //Login After Registered user
                $authdata['email'] = $request->email;
                $authdata['password'] = $request->password;
                if (! $token =  auth()->attempt($authdata)) {
                    return response()->json(['status_code'=> 401,'error' => 'Unauthorized'], 401);
                }
                auth()->user()->forceFill(['device_token'=>$request->device_token])->save();
                $response =  $this->createNewTokenForRegisterLogin($token); 
                return $response;
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Invalid OTP',
                    'data' => []
                ], 400); 
            }
        }else{
            OtpVerifications::where('email',$request->email)->delete();
            $otpCode =  mt_rand(1000,9999);
            OtpVerifications::create([
                    'email'=> $request->email,
                    'otp_verification_code' => $otpCode
            ]);
            $this->sendOTPVerificationEmail($request,$request->password,$otpCode);
            return response()->json([
                'status_code' => 201,
                'message' => 'OTP sent successfully',
                'otp' => $otpCode,
                'data' => []
            ], 201);
        }
    }   

    public function guestRegister($request)
    {
        $validator = Validator::make($request->all(), [
            //'device_token' => 'required',
            'device_uid' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if($request->device_uid=='') $request->device_uid = NULL;
        $gUser = $this->GuestUsers->where('device_uid',$request->device_uid)->first();
        if($gUser){
            $gUser->device_token = $request->device_token;
            $gUser->save();
        }else{
           $gUser = $this->GuestUsers->create(['device_token'=>$request->device_token,'device_uid'=>$request->device_uid]);
           $gUser = $this->GuestUsers->find($gUser->id);
        }
        return response()->json([
            'status_code' => 200,
            'message' => 'User registered successfully',
            'data' => $gUser
        ], 200); 
    } 

    public function resendOtp($request)
        {   
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            try {
                OtpVerifications::where('email',$request->email)->delete();
                $otpCode =  mt_rand(1000,9999);
                OtpVerifications::create([
                        'email'=> $request->email,
                        'otp_verification_code' => $otpCode
                ]);
                $this->sendOTPVerificationEmail($request,$request->password,$otpCode);
                return response()->json([
                    'status_code' => 201,
                    'message' => 'OTP sent successfully',
                    'otp' => $otpCode,
                    'data' => []
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'status_code' => 400,
                    'message' => $e->getMessage(),
                    'data' => []
                ], 400);
            }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $user = auth()->user();
        $user->userToken()->delete();
        $user->userToken()->create(['token'=>$token]);
        return response()->json([
            'status_code' => 200,
            'access_token' => $token,
            'token_type' => 'Bearer',
            //'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], 200);
    }

    protected function createNewTokenForLogin($token){
        $user = auth()->user();
        $user['user_image_path'] = auth()->user()->S3Url;
        $user->userToken()->delete();
        $user->userToken()->create(['token'=>$token]);
        return response()->json([
            'status_code' => 200,
            'message' => 'Login successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            //'expires_in' => auth()->factory()->getTTL() * 60,
            'data' => $user
        ], 200);
    }

    protected function createNewTokenForRegisterLogin($token){
        $user = auth()->user();
        $user['user_image_path'] = auth()->user()->S3Url;
        $user->userToken()->delete();
        $user->userToken()->create(['token'=>$token]);
        return response()->json([
            'status_code' => 200,
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            //'expires_in' => auth()->factory()->getTTL() * 60,
            'data' => $user
        ], 200);
    }

    public function sendWelcomeEmailForUser($user,$password)
    {
        $emailtemplate = EmailTemplate::where('slug', 'create-user')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', ucfirst($user->first_name), $body);
        $body = str_replace('[email]', $user->email, $body);
        $body = str_replace('[password]', $password, $body);
        $jobData = [
            'content' => $body,
            'user' => $user,
            'to' => $user->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public function sendOTPVerificationEmail($request,$password,$otpCode)
    {
        $emailtemplate = EmailTemplate::where('slug', 'mobile-verification-code')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', ucfirst($request->name), $body);
        $body = str_replace('[email]', $request->email, $body);
        $body = str_replace('[OTPCODE]', $otpCode, $body);
        $jobData = [
            'content' => $body,
            'to' => $request->email,
            'subject' => $subject
        ];
        dispatch(new \App\Jobs\SendEmailJob($jobData));
    }

    public function sendForgotPsswordRequest($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
        ]);
        if($validator->fails()){
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
    }

    public function socailLogin($request)
    {   $validator = Validator::make($request->all(), [
            //'email' => 'required|string|email',
            'provider_id' => 'required',
            'provider_name' => 'required|in:facebook,google,apple',
        ]);
        if($validator->fails()){
            return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
        }
        if($request->is_email_edit==1){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'provider_id' => 'required',
                'provider_name' => 'required',
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            OtpVerifications::where('email',$request->email)->delete();
            $otpCode =  mt_rand(1000,9999);
            OtpVerifications::create([
                    'email'=> $request->email,
                    'otp_verification_code' => $otpCode
            ]);
            $this->sendOTPVerificationEmail($request,$request->password,$otpCode);
            return response()->json([
                'status_code' => 201,
                'message' => 'OTP sent successfully',
                'otp' => $otpCode,
                'data' => []
            ], 201);
        }elseif($request->isotp==1){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
                'provider_id' => 'required',
                'provider_name' => 'required',
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            $otpRecord = OtpVerifications::where('email',$request->email)->first();
            if($otpRecord && $otpRecord->otp_verification_code == $request->otp){
                $otpRecord->delete();
                return $this->findOrCreate($request);
            }else{
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Invalid OTP',
                    'data' => []
                ], 400); 
            }
        }else{
            $validator = Validator::make($request->all(), [
                //'email' => 'required|string|email',
                'provider_id' => 'required',
                'provider_name' => 'required',
                'device_uid' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(requestErrorApiResponse($validator->errors()->getMessages()), 422);
            }
            return $this->findOrCreate($request);
        }
    }

    public function findOrCreate($providerdata)
    {
        $account =  LinkedSocialAccount::where('provider_name', $providerdata->provider_name)
                   ->where('provider_id', $providerdata->provider_id)
                   ->first();
        if ($account) {
            $user = $account->user;
            //Login After Registered user
            $authdata['email'] = $user->email;
            auth()->login($user);
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json(['status_code'=> 401,'error' => 'Unauthorized'], 401);
            }
            auth()->user()->forceFill(['device_token'=>$providerdata->device_token])->save();
            if($providerdata->device_uid){
                GuestUsers::where('device_uid',$providerdata->device_uid)->update(['device_token' => NULL]);
            }
            return $this->createNewTokenForRegisterLogin($token); 
        } else {
            $user = User::where('email', $providerdata->email)->first();
            if (! $user) {
                $res['status_code'] = 200;
                $res['message'] = 'User Not found';
                $res['data']['new_user'] = true;
                return response()->json($res, 200);
                $password =  $providerdata->password;
                $providerdata['password'] = bcrypt($password);
                $providerdata['email_verified_at'] = utctodtc_4now();
                $providerdata['image'] = 'noimage.jpg';
                if($providerdata->get('name')== ''){
                    $providerdata['name'] = $providerdata->email;
                }
                $user = User::create($providerdata->all());
                $role = Role::where('slug','customer')->first();
                $user->assignRole([$role->id]);
                $password =  $providerdata->password;
                $this->sendWelcomeEmailForUser($user,$password);
            }
            $user->accounts()->create([
                'provider_id'   => $providerdata->provider_id,
                'provider_name' => $providerdata->provider_name,
            ]);
            auth()->login($user);
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json(['status_code'=> 401,'error' => 'Unauthorized'], 401);
            }
            auth()->user()->forceFill(['device_token'=>$providerdata->device_token])->save();
            return $this->createNewTokenForRegisterLogin($token); 
        }
    }

    public function findOrCreateDirect($providerdata)
    {
        $account =  LinkedSocialAccount::where('provider_name', $providerdata->provider_name)
                   ->where('provider_id', $providerdata->provider_id)
                   ->first();
        if ($account) {
            $user = $account->user;
            //Login After Registered user
            $authdata['email'] = $user->email;
            auth()->login($user);
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json(['status_code'=> 401,'error' => 'Unauthorized'], 401);
            }
            if($providerdata->device_uid){
                GuestUsers::where('device_uid',$providerdata->device_uid)->update(['device_token' => NULL]);
            }
            auth()->user()->forceFill(['device_token'=>$providerdata->device_token])->save();
            return $this->createNewTokenForRegisterLogin($token); 
        } else {
            $user = User::where('email', $providerdata->email)->first();
            if (! $user) {
                $password =  $providerdata->password;
                $providerdata['password'] = bcrypt($password);
                $providerdata['email_verified_at'] = utctodtc_4now();
                $providerdata['image'] = 'noimage.jpg';
                if($providerdata->get('name')== ''){
                    $providerdata['name'] = $providerdata->email;
                }
                $user = User::create($providerdata->all());
                $role = Role::where('slug','customer')->first();
                $user->assignRole([$role->id]);
                if($providerdata->device_uid){
                    GuestUsers::where('device_uid',$providerdata->device_uid)->update(['device_token' => NULL]);
                }
                //$password =  $providerdata->password;
                $this->sendWelcomeEmailForUser($user,$password);
            }
            $user->accounts()->create([
                'provider_id'   => $providerdata->provider_id,
                'provider_name' => $providerdata->provider_name,
            ]);
            auth()->login($user);
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json(['status_code'=> 401,'error' => 'Unauthorized'], 401);
            }
            if($providerdata->device_uid){
                GuestUsers::where('device_uid',$providerdata->device_uid)->update(['device_token' => NULL]);
            }
            auth()->user()->forceFill(['device_token'=>$providerdata->device_token])->save();
            return $this->createNewTokenForRegisterLogin($token); 
        }
    }

    public function logout($request)
    {
        auth()->user()->forceFill(['device_token'=> NULL])->save();
        auth()->user()->userToken()->delete();
        auth()->logout();
        return response()->json(['status_code'=> 200,'message' => 'User successfully signed out'], 200);
    }
}
