<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;


class SocialController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->stateless()
            ->redirect();
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
                'password' => bcrypt(str_random(16)),
                'google_id' => $googleUser->getId(),
                'profile_photo_path' => $googleUser->getAvatar(),
            ]);

            // Generate the token for reset password
            $token = Password::createToken($user);

            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
        }
        
        return redirect()->intended('/dashboard');
    }
}
