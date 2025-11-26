<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Show the user registration form.
     */
    public function showRegistrationForm(): View
    {
        // Render the registration view.
        return view('auth.register');
    }

    /**
     * Handle a new user registration request.
     *
     * This method:
     * - Validates the registration input data.
     * - Creates a new user with a hashed password.
     * - Logs the user in automatically after registration.
     * - Regenerates the session to prevent fixation attacks.
     * - Redirects the user to the cards page with a success message.
     */
    public function register(Request $request)
    {
        // Validate registration input.
        $request->validate([
            'user_name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:user',
            'password' => 'required|min:8|confirmed',
            'phone_no' => 'required|string|min:9|unique:user',
            'birth_date' => 'required|date',
            'profile_pic_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Create the new user.
        User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_no' => $request->phone_no,
            'birth_date' => $request->birth_date,
            'profile_pic_url' => $request->profile_pic_url
        ]);

        // Attempt login for the newly registered user.
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);

        // Regenerate session for security (protection against session fixation).
        $request->session()->regenerate();

        // Redirect to profile page with a success message.
        return redirect()->route('users.show',Auth::id())
            ->withSuccess('You have successfully registered & logged in!');
    }
}
