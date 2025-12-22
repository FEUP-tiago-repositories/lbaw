<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\BusinessOwner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();
            
            // Obter avatar do Google
            $avatar = $google_user->getAvatar();
        
            
            $user = User::where('google_id', $google_user->getId())->first();
            
            if ($user) {
                if ($avatar) {
                    $user->update(['profile_pic_url' => $avatar]);
                }
                
                Auth::login($user);
                return redirect()->route('profile.show', ['id' => $user->id]);
            }
            
            $existing_user = User::where('email', $google_user->getEmail())->first();
            
            if ($existing_user) {
                $updateData = ['google_id' => $google_user->getId()];
                if ($avatar) {
                    $updateData['profile_pic_url'] = $avatar;
                }
                
                $existing_user->update($updateData);

                Auth::login($existing_user);
                return redirect()->route('profile.show', ['id' => $existing_user->id]);
            }
            
            session([
                'oauth_provider' => 'google',
                'oauth_id' => $google_user->getId(),
                'oauth_name' => $google_user->getName(),
                'oauth_email' => $google_user->getEmail(),
                'oauth_avatar' => $avatar  // ← GUARDAR AVATAR
            ]);
            
            return redirect()->route('oauth.select-role');
            
        } catch (Exception $e) {
           
            return redirect()->route('login')
                ->with('error', 'Erro ao autenticar com Google: ' . $e->getMessage());
        }
    }

    /**
     * Completar registo OAuth com role selecionado
     */
    public function completeOAuthRegistration(Request $request)
    {
        $request->validate([
            'role' => 'required|in:customer,business_owner'
        ]);

        try {
            // Obter dados da sessão
            $provider = session('oauth_provider');
            $oauth_id = session('oauth_id');
            $name = session('oauth_name');
            $email = session('oauth_email');
            $avatar = session('oauth_avatar');

            if (!$oauth_id || !$email) {
                return redirect()->route('login')
                    ->with('error', 'Sessão expirada. Tente novamente.');
            }

            // Dividir nome
            $nameParts = explode(' ', $name, 2);
            $firstName = $nameParts[0];
            $surname = isset($nameParts[1]) ? $nameParts[1] : $firstName;

            // Criar utilizador COM FOTO OAUTH se disponível
            $user = User::create([
                'first_name' => $firstName,
                'surname' => $surname,
                'user_name' => $this->generateUsername($email),
                'email' => $email,
                'phone_no' => $this->generateDummyPhone(),
                $provider . '_id' => $oauth_id,
                'profile_pic_url' => $avatar ?? 'images/profile.jpg',  // ← USAR AVATAR DO OAUTH
                'birth_date' => now()->subYears(18)->format('Y-m-d'),
                'password' => null,
                'is_deleted' => false,
                'is_banned' => false
            ]);

            // Criar Customer ou BusinessOwner
            if ($request->role === 'customer') {
                Customer::create(['user_id' => $user->id]);
            } else {
                BusinessOwner::create(['user_id' => $user->id]);
            }

            session()->forget(['oauth_provider', 'oauth_id', 'oauth_name', 'oauth_email', 'oauth_avatar']);

            Auth::login($user);
            return redirect()->route('profile.show', ['id' => $user->id]);

        } catch (Exception $e) {
            Log::error('OAuth Complete Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Erro ao completar registo: ' . $e->getMessage());
        }
    }

    private function generateUsername($email)
    {
        $base = explode('@', $email)[0];
        $username = $base;
        $counter = 1;
        
        while (User::where('user_name', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        
        return $username;
    }

    private function generateDummyPhone()
    {
        do {
            $phone = '9' . rand(10000000, 99999999);
        } while (User::where('phone_no', $phone)->exists());
        
        return $phone;
    }
}
