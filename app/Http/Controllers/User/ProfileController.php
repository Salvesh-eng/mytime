<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show profile settings.
     */
    public function show()
    {
        return view('user.profile.show');
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->update($validated);

        return redirect('/profile')->with('success', 'Profile updated successfully');
    }

    /**
     * Show change password form.
     */
    public function showChangePassword()
    {
        return view('user.profile.change-password');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/profile')->with('success', 'Password updated successfully');
    }
}
