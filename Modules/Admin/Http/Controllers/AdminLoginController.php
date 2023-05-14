<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Modules\Admin\Http\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::ADMIN;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function getAdminLogin()
    { 
        return view('admin::adminLogin');
    }

    public function adminAuth(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($this->guard()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        { 
            $this->guard()->user()->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->getClientIp()
            ]);
        
            if($this->guard()->user()->hasRole(['admin','subadmin'])){
                if($this->guard()->user()->status==0){
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    $request->session()->flash('error', 'Your account is deactivate please contact to Admin');
                    return redirect(RouteServiceProvider::ADMIN_LOGIN);
                }
                $request->session()->flash('success', trans('flash.success.your_account_has_been_successfully_loggedin'));
                return redirect()->route(RouteServiceProvider::ADMIN_DASHBOARD_ROUTE);
            }else{ 
                $this->guard()->logout();
                $request->session()->invalidate();
                $request->session()->flash('error', trans('flash.error.this_is_user_login_area_you_cant_login'));
                return redirect(RouteServiceProvider::ADMIN_LOGIN);
            }
        }else{
              $request->session()->flash('error', trans('flash.error.this_is_user_login_area_you_cant_login'));
                return redirect()->back(); 
        }
    }

    protected function guard() // And now finally this is our custom guard name
    {
         return auth()->guard('admin');
    }
}