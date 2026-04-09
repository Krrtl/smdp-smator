<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   public function showLoginForm(Request $request)
{
    if ($request->session()->has('user_id')) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
}

public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors(['login' => 'Username atau password salah'])->withInput();
    }

    // Simpan session
    $request->session()->put('user_id', $user->id);
    $request->session()->put('user_name', $user->nama);
    $request->session()->put('user_role', $user->role);
    $request->session()->put('user_jabatan', $user->jabatan);

     // 🔥 Arahkan sesuai role
    if ($user->role === 'admin') {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('user.dashboard');
    }
}



public function logout(Request $request)
{
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('portal.home');
}

// 📌 Form ubah password
public function showPasswordForm(Request $request)
{
    // pastikan user sudah login
    if (!$request->session()->has('user_id')) {
        return redirect()->route('login.form');
    }

    return view('auth.ubah-password');
}

// 📌 Proses update password
public function updatePassword(Request $request)
{
    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:6|confirmed',
    ]);

    $user = User::find($request->session()->get('user_id'));

    // cek password lama benar tidak
    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->withErrors(['password_lama' => 'Password lama salah.']);
    }

    // update password
    $user->update(['password' => Hash::make($request->password_baru)]);

    return back()->with('success', 'Password berhasil diperbarui!');
}

}