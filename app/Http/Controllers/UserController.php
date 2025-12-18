<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
    $request->validate([
        'user_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:user,email'],
        'phone_no' => ['required', 'string'],
        'birth_date' => ['required', 'date'],
        'password' => ['required', 'string', 'min:6'],
        'profile_pic_url' => ['nullable', 'image', 'max:2048'],
        'account_type' => ['required', 'in:customer,business'],
    ]);

    $profileImagePath = null;

    if ($request->hasFile('profile_pic_url')) {
        $profileImagePath = $request->file('profile_pic_url')
            ->store('uploads/profile_pics', 'public');
    }

    // Create the user
    $user = User::create([
        'user_name' => $request->user_name,
        'email' => $request->email,
        'phone_no' => $request->phone_no,
        'birth_date' => $request->birth_date,
        'password' => Hash::make($request->password), // hash required for login
        'profile_pic_url' => $profileImagePath ? '/storage/' . $profileImagePath : null,
        'is_banned' => false,
        'is_deleted' => false,
    ]);

    Auth::login($user);

    return redirect()->route('users.show', $user->id)
                     ->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['businessOwner', 'customer'])->findOrFail($id);

        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        
        $user = User::with('spaces')->find($id);

        $unreadCount = Notification::where('user_id', $user->id)
        ->where('is_read', false)
        ->count();
        
        return view('users.profile', compact('user', 'unreadCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $user = User::findOrFail($id);

        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->birth_date = $request->birth_date;

        if ($request->hasFile('profile_pic_url')) {
            $path = $request->file('profile_pic_url')->store('profiles', 'public');
            $user->profile_pic_url = '/storage/' . $path;
        }

        if ($request->account_type === 'customer') {
            DB::table('customer')->insert([
                'user_id' => $user->id,
            ]);
        } else {
            DB::table('business_owner')->insert([
                'user_id' => $user->id,
            ]);
        }

        Auth::login($user);

        $user->save();

        return redirect()->route('users.show', $user->id)
                        ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
