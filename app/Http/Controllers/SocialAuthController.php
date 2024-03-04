<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSocialAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect user to social auth page
     * @param string $provider
     */
    public function handleRedirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleCallback($provider)
    {
        try {
            //get user
            $providerUser = Socialite::driver($provider)->user();
            DB::beginTransaction();
            $user = $this->findOrCreateUser($provider, $providerUser);
            DB::commit();
            //user login
            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('login')
                ->with('status', 'Please try again!');
        }
    }
    /**
     * Find or create user on basis of social accounts  
     * @param string $providerName 
     * @param object $providerUser 
     * @return \App\Models\User 
     */
    public function findOrCreateUser($providerName, $providerUser)
    {
        //find the social accounts
        $social = UserSocialAuth::firstOrNew([
            'provider_user_id' => $providerUser->getId(),
            'provider_type' => $providerName
        ]);

        // If the social authentication already exists, return the associated user
        if ($social->exists) {
            return $social->user;
        }

        // find the user by email
        $user = User::where('email', $providerUser->getEmail())->first();

        // If user doesn't exist, create a new one
        if (!$user) {
            $user = User::create([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'password' => Str::random(8), // Generate a random password
            ]);
        }

        // Associate the user with the social authentication
        $social->user()->associate($user);
        $social->save();

        return $user;
    }
}
