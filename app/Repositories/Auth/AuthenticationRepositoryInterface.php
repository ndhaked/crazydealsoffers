<?php

namespace App\Repositories\Auth;


interface AuthenticationRepositoryInterface
{
    public function login($request);
    
    public function register($request);
    
    public function resendOtp($request);

    public function sendForgotPsswordRequest($request);
    
    public function socailLogin($request);
}

