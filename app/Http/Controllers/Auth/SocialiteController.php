<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider): \Illuminate\Http\RedirectResponse
    {
        $social_user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($social_user, $provider);

        if(!$authUser) {
            return Redirect::to('/dashboard')->with('Error', 'An account for that email already exists!');
        }

        Auth::login($authUser, true);
        return Redirect::to('/dashboard')->with('Congratulations', 'You have been logged in successfully!');
    }

    //create a new user in our database or grab existing user
    private function findOrCreateUser($social_user, $provider)
    {
        if ($authUser = User::where('email', $social_user->email)->first()) {
            if($authUser->provider_id == $social_user->id) {
                return $authUser;
            } else {
                $existedUser = User::where('email', $social_user->email)->first();
                $existedUser->update([
                    'provider_id' => $social_user->id,
                    'provider_name' => $provider,
                ]);

                return $existedUser;
            }
        }

        return User::Create([
            'provider_id' => $social_user->id,
            'name' => $social_user->name,
            'email' => $social_user->email,
            'provider_name' => $provider
        ]);
    }
}
