<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class FacebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')
            ->scopes(['email', 'public_profile'])
            ->redirect();
    }

    public function callbackFacebook()
    {
        try {
            $facebook_user = Socialite::driver('facebook')->stateless()->user();
            
            // Obter avatar do Facebook
            $avatar = $facebook_user->getAvatar();

            // Verificar se já existe utilizador
            $user = User::where('facebook_id', $facebook_user->getId())->first();

            if ($user) {
                // Utilizador já existe - atualizar foto se disponível
                if ($avatar) {
                    $user->update(['profile_pic_url' => $avatar]);
                }
                
                Auth::login($user);
                return redirect()->route('profile.show', ['id' => $user->id]);
            }

            // Verificar email existente
            $existing_user = User::where('email', $facebook_user->getEmail())->first();

            if ($existing_user) {
                // Associar Facebook ID e atualizar foto
                $updateData = ['facebook_id' => $facebook_user->getId()];
                if ($avatar) {
                    $updateData['profile_pic_url'] = $avatar;
                }
                
                $existing_user->update($updateData);
                Log::info('Linked Facebook to existing user', [
                    'user_id' => $existing_user->id,
                    'avatar_updated' => !empty($avatar)
                ]);
                
                Auth::login($existing_user);
                return redirect()->route('profile.show', ['id' => $existing_user->id]);
            }

            
            session([
                'oauth_provider' => 'facebook',
                'oauth_id' => $facebook_user->getId(),
                'oauth_name' => $facebook_user->getName(),
                'oauth_email' => $facebook_user->getEmail(),
                'oauth_avatar' => $avatar 
            ]);

            return redirect()->route('oauth.select-role');

        } catch (Exception $e) {
            Log::error('Facebook OAuth Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Erro ao autenticar com Facebook: ' . $e->getMessage());
        }
    }
}
