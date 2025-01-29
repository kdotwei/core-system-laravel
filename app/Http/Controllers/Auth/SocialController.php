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
        
        return redirect()->intended('dashboard');
    }

    /**
     * Update user information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateUserInformation(Request $request)
    {
        $user = Auth::user();
    
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
    
        // Handle profile photo upload if provided
        if ($request->hasFile('profile_photo_path')) {
            $user->profile_photo_path = $request->file('profile_photo_path')->store('profile_photos', 'public');
            $user->save();
        }
    
        return redirect()->route('dashboard')->with('success', 'User information updated successfully.');
    }
}
