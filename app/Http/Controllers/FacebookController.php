<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class FacebookController extends Controller
{
    /**
     * Redirect to Facebook authentication page
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook authentication callback
     */
    public function callbackFacebook()
    {
        try {
            $facebook_user = Socialite::driver('facebook')->stateless()->user();

            // Verificar se já existe utilizador com este Facebook ID
            $user = User::where('facebook_id', $facebook_user->getId())->first();

            if (!$user) {
                // Verificar se existe utilizador com o mesmo email
                $existing_user = User::where('email', $facebook_user->getEmail())->first();

                if ($existing_user) {
                    // Associar Facebook ID ao utilizador existente
                    $existing_user->update([
                        'facebook_id' => $facebook_user->getId(),
                        'profilepicurl' => $facebook_user->getAvatar() ?? $existing_user->profilepicurl
                    ]);
                    Auth::login($existing_user);
                } else {
                    // Criar novo utilizador
                    // Dividir nome completo em firstname e surname
                    $nameParts = explode(' ', $facebook_user->getName(), 2);
                    $firstname = $nameParts[0];
                    $surname = isset($nameParts[1]) ? $nameParts[1] : '';

                    $new_user = User::create([
                        'firstname' => $firstname,
                        'surname' => $surname,
                        'username' => $this->generateUsername($facebook_user->getEmail()),
                        'email' => $facebook_user->getEmail(),
                        'phoneno' => $this->generateDummyPhone(),
                        'facebook_id' => $facebook_user->getId(),
                        'profilepicurl' => $facebook_user->getAvatar() ?? 'images/profile.jpg',
                        'birthdate' => now()->subYears(18), // Placeholder - pode ajustar depois
                        'password' => null
                    ]);

                    Auth::login($new_user);
                }
            } else {
                Auth::login($user);
            }

            return redirect()->intended('/');

        } catch (Exception $e) {
            \Log::error('Facebook OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Erro ao autenticar com Facebook. Tente novamente.');
        }
    }

    /**
     * Generate unique username from email
     */
    private function generateUsername($email)
    {
        $base = explode('@', $email)[0];
        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Generate dummy phone number (temporary)
     */
    private function generateDummyPhone()
    {
        do {
            $phone = '9' . rand(10000000, 99999999);
        } while (User::where('phoneno', $phone)->exists());

        return $phone;
    }
}
