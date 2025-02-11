<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate required fields only
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password field is required.',
        ]);

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['invalid' => 'Invalid credentials.'])->withInput();
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        // Validate password inputs
        $request->validate([
            'old_password' => 'required|current_password',
            'new_password' => [
                'required',
                'min:8',
                'regex:/[^A-Za-z0-9]/', // At least one special character
            ],
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'The old password field is required.',
            'old_password.current_password' => 'The old password is incorrect.',
            'new_password.required' => 'The new password field is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.regex' => 'The new password must contain at least one special character.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.same' => 'The confirm password does not match the new password.',
        ]);

        // Update Password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Logout user after changing password
        Auth::logout();

        return redirect('/login')->with('success', 'Password changed successfully. Please log in again.');
    }
}
