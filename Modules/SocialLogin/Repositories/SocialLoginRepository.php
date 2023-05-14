<?php

namespace Modules\SocialLogin\Repositories;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Modules\SocialLogin\Repositories\SocialAccountService as accountService;

class SocialLoginRepository implements SocialLoginInterface {

     /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectToLogin = RouteServiceProvider::LOGIN;

    function __construct(accountService $accountService) {
        $this->accountService = $accountService;
    }

    public function handleProviderCallback($provider,$request)
    {
        try {
            $user = \Socialite::with($provider)->user();
        } catch (\Exception $e) {
            return redirect($this->redirectToLogin);
        }
        $authUser = $this->accountService->findOrCreate(
            $user,
            $provider
        );
        auth()->login($authUser, true);
        auth()->user()->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->getClientIp()
        ]);
        $request->session()->flash('success', trans('flash.success.your_account_has_been_successfully_loggedin'));
        return redirect()->to($this->redirectTo);
    } 
}
