<?php

namespace Modules\SocialLogin\Repositories;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Modules\Roles\Entities\Role;
use App\Models\User;
use Modules\SocialLogin\Entities\LinkedSocialAccount;

class SocialAccountService
{
    public function findOrCreate(ProviderUser $providerUser, $provider)
    {
        $account =  LinkedSocialAccount::where('provider_name', $provider)
                   ->where('provider_id', $providerUser->getId())
                   ->first();

        if ($account) {
            return $account->user;
        } else {

        $user = User::where('email', $providerUser->getEmail())->first();

        if (! $user) {
            $user = User::create([  
                'email' => $providerUser->getEmail(),
                'name'  => $providerUser->getName(),
                'email_verified_at' => now(),
            ]);
            $role = Role::where('slug','borrowers')->first();
            $user->assignRole([$role->id]);
        }

        $user->accounts()->create([
            'provider_id'   => $providerUser->getId(),
            'provider_name' => $provider,
        ]);

        return $user;

        }
    }
}
