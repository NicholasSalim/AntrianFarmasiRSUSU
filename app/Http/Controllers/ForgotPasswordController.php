<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; // Add this at the top
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
   
public function sendCode(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = User::where('email', $request->email)->first();
    $code = rand(100000, 999999);
    $expiresAt = Carbon::now()->addMinutes(2); // Set expiration time (2 minutes)

    $user->update([
        'reset_code' => $code,
        'reset_code_expires_at' => $expiresAt
    ]);

    try {
        Mail::raw("Your verification code is: $code", function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset Code');
        });
    } catch (\Exception $e) {
        Log::error('Mail sending failed: ' . $e->getMessage()); // Log error
        return back()->withErrors(['email' => 'Gagal mengirim kode verifikasi. Coba lagi.']);
    }

    return back()->with('success', 'Kode verifikasi telah terkirim ke email anda.');
}


public function verifyCode(Request $request)
{
    $request->validate([
        'code' => 'required|numeric',
    ]);

    $user = User::where('reset_code', $request->code)
                ->where('reset_code_expires_at', '>', Carbon::now())
                ->first();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Kode Verifikasi Tidak Sesuai.'], 400);
    }

    // Store email in session for password reset
    session(['reset_email' => $user->email]);

    return response()->json(['success' => true]);
}



public function resetPassword(Request $request)
{
    $request->validate([
        'password' => [
            'required',
            'string',
            'min:8',
            function ($attribute, $value, $fail) {
                if (!preg_match('/[0-9]/', $value)) {
                    $fail('Password harus memiliki setidaknya satu angka.');
                }
                if (!preg_match('/[\W]/', $value)) {
                    $fail('Password harus memiliki setidaknya satu karakter spesial.');
                }
            },
        ],
        'password_confirmation' => 'required|same:password',
    ], [
        'password.required' => 'Kolom password harus diisi.',
        'password.min' => 'Password harus memiliki minimal 8 karakter.',
        'password_confirmation.required' => 'Kolom konfirmasi password harus diisi.',
        'password_confirmation.same' => 'Konfirmasi password tidak cocok dengan password baru.',
    ]);

    // Retrieve the email from session instead of user input
    $email = session('reset_email');

    if (!$email) {
        return back()->withErrors(['password' => 'Session expired. Please restart the reset process.'])->with('modalOpen', true);
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        return back()->withErrors(['password' => 'User not found.'])->with('modalOpen', true);
    }

    // Update password and clear session
    $user->update([
        'password' => Hash::make($request->password),
        'reset_code' => null,
        'reset_code_expires_at' => null,
    ]);

    session()->forget('reset_email'); // Clear session after success

    return redirect('/login')->with('success', 'Password has been reset. You can now log in.');
}
}
