<?php

namespace App\Services;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthService
{
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::firstOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'fname' => $socialUser->getName(),
            'lname' => '',
            'email_verified_at' => now(),
            'password' => bcrypt(Str::random(16)),
        ]);

        Auth::login($user);

        return $user;
    }
}
