<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController
{   

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();

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
            'password' => 'required|string',
            'birth_date' => 'required|date|before:-18 years',
            'profile_pic_url' => 'nullable|url',
        ]);

        $data = [
            'user_name' => $request->user_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => bcrypt($request->password),
            'birth_date' => $request->birth_date,
            'profile_pic_url' => $request->profile_pic_url,
            'is_banned' => false,
        ];

        User::create($data);

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
            'user_name' => 'required|string',
            'email' => 'required|email|unique:user,email,' . $id,
            'phone_no' => 'required|string|unique:user,phone_no,' . $id,
            'password' => 'nullable|string|confirmed',
            'birth_date' => 'required|date|before:-18 years',
            'profile_pic_url' => 'nullable|url',
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
