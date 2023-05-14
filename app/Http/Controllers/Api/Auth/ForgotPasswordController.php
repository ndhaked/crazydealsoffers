<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Foundation\Api\Auth\ApiSendsPasswordResetEmails;
use App\Http\Controllers\Api\BaseController;

class ForgotPasswordController extends BaseController {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

use ApiSendsPasswordResetEmails;

    public function __construct(User $User) {
        $this->User = $User;
    }

    /**
     * Get the Login validation rules.
     *
     * @return array
     */
    protected function rules() {
        return [
            'email' => 'required|email',
        ];
    }
}
