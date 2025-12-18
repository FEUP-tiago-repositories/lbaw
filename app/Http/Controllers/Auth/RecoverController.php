<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RecoverController extends Controller
{
    public function __construct()
    {
    }

    public function sendRecoveryEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = DB::table('user')->where('email', $request->email)->first();
    
        if (!$user) {
            return back()
                ->withErrors(['email' => 'No account found with that email address.'])
                ->withInput();
        }
    
        $token = Str::random(64);
    
        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
            'expires_at' => now()->addHour(),
        ]);
    
        // Send email
        $resetLink = url("/reset-password/$token");
    
        Mail::raw("Click here to reset your password:\n\n$resetLink", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset');
        });
    
        return redirect(route('login'));
    }

    public function showResetForm(Request $request, $token)
    {
        $record = DB::table('password_resets')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
    
        if (!$record) {
            return redirect(route('login'));
        }
    
        return view('auth.reset_password', ['token' => $token]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $record = DB::table('password_resets')
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();
    
        if (!$record) {
            return redirect(route('login'));
        }
    
        $user = User::findOrFail($record->user_id);
        $user->password = Hash::make($request->password);
        $user->save();
    
        DB::table('password_resets')->where('id', $record->id)->delete();
    
        return redirect(route('login'));
    }
    

}
