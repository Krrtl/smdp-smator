<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::all();
        return view('admin.kelola-user', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('admin.tambah-user');
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'jabatan' => 'required|string|max:255',
            'role' => 'required|in:admin,user,tatatertib',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'jabatan' => $request->jabatan,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('kelola.user')->with('success', 'User berhasil diperbarui!');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'jabatan' => 'required|string|max:255',
            'role' => 'required|in:admin,user,tatatertib',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
            'role' => $request->role,
        ]);

        return redirect()->route('kelola.user')->with('success', 'User berhasil ditambahkan!');
    }

    // Menghapus user
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('kelola.user')->with('success', 'User berhasil dihapus.');
    }
public function dashboard()
{
    $userName = session('user_name'); // tetap pakai session
    $userId = session('user_id'); // pastikan diset waktu login
    $totalDokumen = \App\Models\Dokumen::where('user_id', $userId)->count();

    return view('user.dashboard', compact('userName', 'totalDokumen'));
}

}