<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\BusinessOwner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserManagementController
{   

    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $users = User::with(['businessOwner', 'customer'])->orderBy('id', 'asc')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'email' => 'required|email|unique:user,email',
            'phone_no' => 'required|string|unique:user,phone_no',
            'password' => 'required|string|min:6',
            'birth_date' => 'required|date|before:-18 years',
            'profile_pic_url' => 'nullable|image|max:2048',
            'account_type' => 'required|in:customer,business_owner',
        ]);

        $profileImagePath = null;

        if ($request->hasFile('profile_pic_url')) {
            $profileImagePath = $request->file('profile_pic_url')
                ->store('uploads/profile_pics', 'public');
        }

         $user = User::create([
            'first_name' => $request->first_name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'profile_pic_url' => $profileImagePath ? '/storage/' . $profileImagePath : null,
            'is_banned' => false,
            'is_deleted' => false,
        ]);

        if ($request->account_type === 'customer') {
            Customer::create(['user_id' => $user->id]);
        } else {
            BusinessOwner::create(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:250',
            'surname' => 'required|string|max:250',
            'user_name' => 'required|string',
            'email' => 'required|email|unique:user,email,' . $id,
            'phone_no' => 'required|string|unique:user,phone_no,' . $id,
            'birth_date' => 'required|date|before:-18 years',
            'profile_pic_url' => 'nullable|image|max:2048',
            'is_banned' => 'required|boolean',
        ]);

        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->birth_date = $request->birth_date;
        $user->profile_pic_url = $request->profile_pic_url;
        $user->is_banned = $request->is_banned;

        if ($request->hasFile('profile_pic_url')) {
            $path = $request->file('profile_pic_url')->store('profiles', 'public');
            $user->profile_pic_url = '/storage/' . $path;
        }

        if ($request->account_type === 'customer') {
            DB::table('customer')->updateOrInsert(
                ['user_id' => $user->id],
                []
            );
        } else {
            DB::table('business_owner')->updateOrInsert(
                ['user_id' => $user->id],
                []
            );
        }
        $user->save();

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
