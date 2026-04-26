<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed.');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update social ID if not set
            $idField = $provider . '_id';
            if (!$user->$idField) {
                $user->update([
                    $idField => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            }
        } else {
            // Create a new user
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                $provider . '_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'password' => null, // Social user
                'status' => 'active',
            ]);
        }

        Auth::login($user);

        // Check if user has a subscription plan. If not, redirect to pricing/onboarding.
        if (!$user->subscription_plan_id) {
            // Assuming we have a route for plan selection
            // For now, redirect to dashboard or a specific onboarding page
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
