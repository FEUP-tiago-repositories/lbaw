<?php

namespace App\Http\Controllers;

use App\Models\BusinessOwner;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use AuthorizesRequests;

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
            'first_name' => ['required', 'string', 'min:2', 'max:15', 'regex:/^[A-Za-z]+$/'],
            'surname' => ['required', 'string', 'min:2', 'max:15', 'regex:/^[A-Za-z]+$/'],
            'user_name' => ['required', 'string', 'min:2', 'max:15', 'unique:user,user_name', 'regex:/^[A-Za-z0-9_]+$/'],
            'email' => ['required', 'email', 'unique:user,email'],
            'phone_no' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
            'birth_date' => ['required', 'date'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'profile_pic_url' => ['nullable', 'image', 'max:2048'],
            'role' => ['required_without:account_type', 'in:customer,business_owner'],
            'account_type' => ['required_without:role', 'in:customer,business_owner'],
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_pic_url')) {
            $file = $request->file('profile_pic_url');
            $profilePicName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/profiles'), $profilePicName);
            $profileImagePath = 'images/uploads/profiles/'.$profilePicName;
        }

        $role = $request->input('role') ?? $request->input('account_type');

        $user = User::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'birth_date' => $request->birth_date,
            'password' => Hash::make($request->password), // hash required for login
            'profile_pic_url' => $profileImagePath,
            'is_banned' => false,
            'is_deleted' => false,
        ]);

        if ($role === 'customer') {
            Customer::create(['user_id' => $user->id]);
        } else {
            BusinessOwner::create(['user_id' => $user->id]);
        }

        Auth::login($user);

        return redirect()->route('users.show', $user->id)
            ->with('success', 'Account sucessfully created!');
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

        // Carrega spaces para Business Owner
        $user = User::with('spaces')->find($id);

        // Carrega favoritos para Customer
        $favoritedSpaces = null;
        if ($user->customer) {
            $favoritedSpaces = $user->customer->favoritedSpaces()->get();
        }

        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('users.profile', compact('user', 'unreadCount', 'favoritedSpaces'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:15', 'regex:/^[A-Za-z]+$/'],
            'surname' => ['required', 'string', 'min:2', 'max:15', 'regex:/^[A-Za-z]+$/'],
            'user_name' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[A-Za-z0-9_]+$/',
                Rule::unique('user', 'user_name')->ignore($user->id),
            ],
            'email' => ['required', 'email', 'max:100', Rule::unique('user', 'email')->ignore($user->id)],
            'phone_no' => ['required', 'regex:/^[0-9]+$/'],
            'birth_date' => ['required', 'date', 'before_or_equal:'.Carbon::now()->subYears(18)->toDateString()],
            'profile_pic_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->first_name = $validated['first_name'];
        $user->surname = $validated['surname'];
        $user->user_name = $validated['user_name'];
        $user->email = $validated['email'];
        $user->phone_no = $validated['phone_no'];
        $user->birth_date = $validated['birth_date'];

        if ($request->hasFile('profile_pic_url')) {
            $file = $request->file('profile_pic_url');
            $profilePicName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/uploads/profiles'), $profilePicName);
            $user->profile_pic_url = 'images/uploads/profiles/'.$profilePicName;
        }
        $user->save();

        return redirect()->route('users.show', $user->id)
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = Auth::user();

        Auth::logout();

        $user->delete();

        return redirect()->route('home');
    }
}
