<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Show all users.
     */
    public function index()
    {
        $users = User::where('role', 'user')->paginate(15);
        return view('admin.users.index', ['users' => $users]);
    }

    /**
     * Show create user form.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:user,admin',
        ]);

        $password = $validated['password'] ?? Str::random(12);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => $validated['role'],
            'status' => 'active',
        ]);

        return redirect('/admin/users')->with('success', 'User created successfully. Password: ' . $password);
    }

    /**
     * Show edit user form.
     */
    public function edit(User $user)
    {
        if ($user->role === 'admin' && $user->id !== auth()->id()) {
            return redirect('/admin/users')->with('error', 'Unauthorized');
        }
        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        return redirect('/admin/users')->with('success', 'User updated successfully');
    }

    /**
     * Deactivate user.
     */
    public function deactivate(User $user)
    {
        $user->update(['status' => 'inactive']);
        return redirect('/admin/users')->with('success', 'User deactivated');
    }

    /**
     * Activate user.
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'active']);
        return redirect('/admin/users')->with('success', 'User activated');
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $user)
    {
        $newPassword = Str::random(12);
        $user->update(['password' => Hash::make($newPassword)]);
        return redirect('/admin/users')->with('success', 'Password reset. New password: ' . $newPassword);
    }
}
