<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch(\Exception $e) {
            return redirect('\login')->with('error', 'Cannot login, please retry.');
        }

        // Check if the user exist
        $user = User::where('email', $googleUser->getEmail())->first();

        if($user) {
            // If user exist
            Auth::login($user);
        } else {
            // If user does not exist
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::str_random(16)),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            Auth::login($user);
        }
        
        return redirect()->intended('/dashboard');
    }
}
