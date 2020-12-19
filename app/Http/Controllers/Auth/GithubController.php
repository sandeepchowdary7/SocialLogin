<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GithubController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return RedirectResponse
     */
    public function redirectToGithub(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return Application|\Illuminate\Http\RedirectResponse|Redirector
     */
    public function handleGithubCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
            $finduser = User::where('github_id', $user->id)->first();

            if($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'github_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);

                Auth::login($newUser);
                return redirect('/dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
