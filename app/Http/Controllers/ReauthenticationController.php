<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ReauthenticationController extends Controller
{
    /**
     * Show the reauthentication form.
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        return view('auth.reauthenticate');
    }

    /**
     * Handle reauthentication request.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        // Verify the password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'The password is incorrect.',
            ]);
        }

        // Update the last auth time
        Session::put('last_auth_time', now()->timestamp);
        Session::forget('requires_reauthentication');

        // Redirect to intended URL or financial dashboard
        $intendedUrl = Session::pull('intended_url', '/admin/financial');
        return redirect($intendedUrl);
    }
}
