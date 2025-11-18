<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class GoogleOAuthController extends Controller
{
    /**
     * Redirect user to Google for authentication
     */
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect_uri'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');
        $state = $request->query('state');

        if (!$code) {
            return redirect('/login')->with('error', 'Google authentication failed. Please try again.');
        }

        try {
            // Exchange code for access token
            $response = Http::post('https://oauth2.googleapis.com/token', [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => config('services.google.redirect_uri'),
            ]);

            if ($response->failed()) {
                return redirect('/login')->with('error', 'Failed to authenticate with Google.');
            }

            $accessToken = $response->json('access_token');

            // Get user info from Google
            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if ($userResponse->failed()) {
                return redirect('/login')->with('error', 'Failed to retrieve user information from Google.');
            }

            $googleUser = $userResponse->json();

            // Find or create user
            $user = User::where('oauth_provider', 'google')
                ->where('oauth_id', $googleUser['id'])
                ->first();

            if (!$user) {
                // Check if email already exists with traditional login
                $existingUser = User::where('email', $googleUser['email'])->first();

                if ($existingUser) {
                    // Link Google account to existing user
                    $existingUser->update([
                        'oauth_provider' => 'google',
                        'oauth_id' => $googleUser['id'],
                    ]);
                    $user = $existingUser;
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser['name'] ?? 'Google User',
                        'email' => $googleUser['email'],
                        'username' => $this->generateUsername($googleUser['email']),
                        'oauth_provider' => 'google',
                        'oauth_id' => $googleUser['id'],
                    ]);
                }
            }

            // Create session for user
            Session::put('user_id', $user->id);
            Session::put('username', $user->username);
            Session::put('name', $user->name);

            return redirect('/')->with('success', 'Login berhasil dengan Google! Selamat datang, ' . $user->name . '.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Error occurred during Google authentication.');
        }
    }

    /**
     * Generate a unique username from email
     */
    private function generateUsername($email)
    {
        $baseUsername = explode('@', $email)[0];
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
