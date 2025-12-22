<?php
namespace App\Http\Controllers;

use App\Models\BanAppeal;
use App\Models\User;
use App\Models\Ban;
use Illuminate\Http\Request;

class BanAppealController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'appeal' => 'required|string',
        'user_id' => 'required|exists:user,id',
    ]);

    $userId = $request->user_id;

    if (!$userId) {
        return back()->withErrors(['appeal' => 'Session expired. Please try logging in again.'], 'appeal');
    }

    $user = User::find($userId);
    
    if (!$user) {
        return back()->withErrors(['appeal' => 'User not found.'], 'appeal');
    }

    $ban = Ban::where('user_id', $user->id)->first();
    
    if (!$ban) {
        return back()->withErrors(['appeal' => 'No ban record found.'], 'appeal');
    }

    BanAppeal::create([
        'user_id'   => $user->id,
        'ban_id'    => $ban->id,
        'appeal'    => $request->appeal,
        'time_stamp'=> now(),
    ]);

    return back()->with('appeal_success', 'Your appeal has been submitted.');
}
}