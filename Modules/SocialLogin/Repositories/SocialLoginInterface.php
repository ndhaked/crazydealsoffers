<?php

namespace Modules\SocialLogin\Repositories;


interface SocialLoginInterface
{
    public function handleProviderCallback($provider,$request);

}