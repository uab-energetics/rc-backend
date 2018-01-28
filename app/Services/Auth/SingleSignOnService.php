<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 1/27/18
 * Time: 10:52 PM
 */

namespace App\Services\Auth;

use App\LinkedSocialAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;


class SingleSignOnService
{
    public function findOrCreate(ProviderUser $providerUser, $provider) {
        $account = LinkedSocialAccount::where('provider_name', $provider)
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
                ]);
            }

            $user->accounts()->create([
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;

        }
    }
}