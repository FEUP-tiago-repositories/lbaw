<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Show the login form.
     *
     * If the user is already authenticated, redirect them
     * to the cards dashboard instead of showing the form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('users.show', Auth::id());
        } else {
            return view('auth.login');
        }
    }

    /**
     * Process an authentication attempt.
     *
     * Validates the incoming request, checks the provided
     * credentials, and logs the user in if successful.
     * The session is regenerated to protect against session fixation.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // Validate the request data.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->is_banned) {
            return back()
                ->with('banned', true)
                ->with('ban_motive', $user->ban->motive ?? 'No reason provided.')
                ->with('user_id', $user->id) 
                ->with('error', 'Your account has been banned.')
                ->onlyInput('email');
        }

        if ($user && $user->is_deleted) {
            return back()
                ->with('deleted', true);
        }
 
        // Attempt to authenticate and log in the user.
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate the session ID to prevent session fixation attacks.
            $request->session()->regenerate();
     
            return redirect()->intended(
                route('users.show', Auth::id())
            );
        }
        return back()->with('error', 'Incorrect email or password.')
                    ->onlyInput('email');
    }
}
