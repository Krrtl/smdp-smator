<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Halaman Dashboard
    public function index(Request $request)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login.form');
        }

        $nama = $request->session()->get('user_name');
        $jabatan = $request->session()->get('user_jabatan');
        $role = $request->session()->get('user_role');

        // Hitung jumlah user berdasarkan jabatan
        $data = [
            'Kesiswaan' => User::where('jabatan', 'Kesiswaan')->count(),
            'Kurikulum' => User::where('jabatan', 'Kurikulum')->count(),
            'Humas' => User::where('jabatan', 'Humas')->count(),
            'Tata Usaha' => User::where('jabatan', 'Tata Usaha')->count(),
            'BK' => User::where('jabatan', 'BK')->count(),
            'Sarana Prasarana' => User::where('jabatan', 'Sarana Prasarana')->count(),
            'Guru Pengajar' => User::where('jabatan', 'Guru Pengajar')->count(),
            'Tata Tertib' => User::where('jabatan', 'Tata Tertib')->count(),
        ];

        return view('admin.dashboard', compact('nama', 'jabatan', 'role', 'data'));
    }

    // Menampilkan detail user berdasarkan bidang
    public function detail($jabatan)
    {
        $users = User::where('jabatan', $jabatan)->get();
        return view('admin.detail-bidang', compact('users', 'jabatan'));
    }
}