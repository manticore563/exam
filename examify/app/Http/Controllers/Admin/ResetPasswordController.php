<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Display the reset password form.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.reset-password', compact('users'));
    }

    /**
     * Reset the password for a selected user.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.reset-password')->with('success', 'Password reset successfully.');
    }
}
?>