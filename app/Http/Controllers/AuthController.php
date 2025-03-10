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
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Tolong masukkan Email yang terdaftar.',
            'password.required' => 'Password harus diisi.',
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


   public function changePassword(Request $request)
   {
       $request->validate([
           'old_password' => 'required|current_password',
           'new_password' => [
               'required',
               'string',
               'min:8',
               function ($attribute, $value, $fail) {
                   if (!preg_match('/[0-9]/', $value)) {
                       $fail('Password harus memiliki minimal satu angka.');
                   }
                   if (!preg_match('/[\W]/', $value)) {
                       $fail('Password harus memiliki simbol/karakter spesial.');
                   }
               },
           ],
           'confirm_password' => 'required|same:new_password',
       ], [
           'old_password.required' => 'Password lama harus diisi.',
           'old_password.current_password' => 'Password lama salah.',
           'new_password.required' => 'Password baru harus diisi.',
           'new_password.min' => 'Password baru minimal 8 huruf.',
           'confirm_password.required' => 'Konfirmasi Password baru harus diisi.',
           'confirm_password.same' => 'Konfirmasi Password baru tidak sesuai.',
       ]);
   
       // Update the password
       $user = Auth::user();
       $user->password = Hash::make($request->new_password);
       $user->save();
   
       // Logout user after password change
       Auth::logout();
   
       return redirect('/login')->with('success', 'Password changed successfully. Please log in again.');
   }
   
    
    
}
