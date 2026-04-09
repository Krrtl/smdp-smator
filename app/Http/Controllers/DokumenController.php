<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DokumenController extends Controller
{
    // 📁 Daftar dokumen (halaman Upload Dokumen)
    public function index(Request $request)
    {
    $userId = $request->session()->get('user_id');
if (!$userId) {
    return redirect()->route('login.form')->with('error', 'Silakan login terlebih dahulu.');
}

$user = \App\Models\User::find($userId);


        // Ambil bidang dari session, tapi aman kalau tidak ada
        $userBidang = $request->session()->get('user_jabatan', $user->jabatan ?? 'Tidak Diketahui');

        // Jika admin -> tampilkan semua dokumen
        if ($user->role === 'admin') {
            $dokumens = Dokumen::latest()->get();
        } 
        // Jika user biasa -> tampilkan dokumen miliknya saja
        else {
            $dokumens = Dokumen::where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('admin.upload', compact('dokumens'));
    }

    // 📝 Form tambah dokumen
    public function create()
    {
        return view('admin.upload-create');
    }

    // 💾 Simpan dokumen baru
    public function store(Request $request)
{
    $request->validate([
        'nama_dokumen' => 'required',
        'jenis' => 'required',
        'tahun' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:4096',
    ]);

    $filePath = $request->file('file')->store('dokumen', 'public');

    // Gunakan session, bukan Auth::id()
    $userId = $request->session()->get('user_id');
    $bidang = $request->session()->get('user_jabatan', 'Umum');

    \App\Models\Dokumen::create([
        'user_id' => $userId,
        'nama_dokumen' => $request->nama_dokumen,
        'jenis' => $request->jenis,
        'tahun' => $request->tahun,
        'file_path' => $filePath,
        'bidang' => $bidang,
    ]);

    return redirect()->route('upload.index')->with('success', 'Dokumen berhasil diupload.');
}

    // ✏️ Form edit dokumen
 public function edit($id, Request $request)
{
    $dokumen = \App\Models\Dokumen::findOrFail($id);

    // Ambil role dari session, bukan dari Auth
    $userRole = $request->session()->get('user_role');

    // Misal: hanya admin atau pemilik dokumen yang boleh edit
    if ($userRole !== 'admin' && $dokumen->user_id != $request->session()->get('user_id')) {
        return redirect()->route('upload.index')->withErrors('Anda tidak memiliki izin untuk mengedit dokumen ini.');
    }

    return view('admin.upload-edit', compact('dokumen'));

}


    public function update(Request $request, $id)
{
    $dokumen = Dokumen::findOrFail($id);

    $userId = session('user_id');
    $userRole = session('user_role');

    // Cek izin akses
    if ($dokumen->user_id != $userId && $userRole !== 'admin') {
        abort(403, 'Kamu tidak punya izin untuk mengedit dokumen ini.');
    }

    $request->validate([
        'nama_dokumen' => 'required',
        'jenis' => 'required',
        'tahun' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:4096',
    ]);

    $data = $request->only(['nama_dokumen', 'jenis', 'tahun']);

    if ($request->hasFile('file')) {
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $data['file_path'] = $request->file('file')->store('dokumen', 'public');
    }

    $dokumen->update($data);

    return redirect()->route('upload.index')->with('success', 'Dokumen berhasil diperbarui.');
}

    // ❌ Hapus dokumen
    public function destroy($id)
{
    $dokumen = Dokumen::findOrFail($id);

    $userId = session('user_id');
    $userRole = session('user_role');

    // Cek apakah user punya izin hapus
    if ($dokumen->user_id != $userId && $userRole !== 'admin') {
        abort(403, 'Kamu tidak punya izin untuk menghapus dokumen ini.');
    }

    // Hapus file dari storage jika ada
    if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
        Storage::disk('public')->delete($dokumen->file_path);
    }

    // Hapus data dari database
    $dokumen->delete();

    return redirect()->route('upload.index')->with('success', 'Dokumen berhasil dihapus.');
}


    // 📋 Halaman semua dokumen (lihat & download)
    public function showAll(Request $request)
{
    $query = Dokumen::query();

    // Kalau user bukan admin, hanya tampilkan dokumen miliknya
    if (session('user_role') !== 'admin') {
        $query->where('user_id', session('user_id'));
    }

    // Fitur search berdasarkan nama dokumen, tahun, atau bidang
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_dokumen', 'like', "%{$search}%")
              ->orWhere('tahun', 'like', "%{$search}%")
              ->orWhere('bidang', 'like', "%{$search}%");
        });
    }

    $dokumens = $query->get();

    return view('admin.dokumen', compact('dokumens'));
}


    // ⬇️ Download dokumen
    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $path = storage_path('app/public/' . $dokumen->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($path);
    }

    // 👁️ Lihat dokumen langsung di browser
    public function view($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $path = storage_path('app/public/' . $dokumen->file_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }

   // ====================================================
// 📦 BAGIAN USER - Upload Dokumen (mirip admin tapi view & redirect beda)
// ====================================================

public function userIndex(Request $request)
{
    $userId = session('user_id');
    if (!$userId) return redirect()->route('login.form');

    $dokumens = Dokumen::where('user_id', $userId)->latest()->get();
    return view('user.upload', compact('dokumens'));
}

public function userCreate()
{
    return view('user.upload-create');
}

public function userStore(Request $request)
{
    $request->validate([
        'nama_dokumen' => 'required',
        'jenis' => 'required',
        'tahun' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:4096',
    ]);

    $filePath = $request->file('file')->store('dokumen', 'public');
    $userId = session('user_id');
    $bidang = session('user_jabatan', 'Umum');

    Dokumen::create([
        'user_id' => $userId,
        'nama_dokumen' => $request->nama_dokumen,
        'jenis' => $request->jenis,
        'tahun' => $request->tahun,
        'file_path' => $filePath,
        'bidang' => $bidang,
    ]);

    return redirect()->route('user.upload.index')->with('success', 'Dokumen berhasil diupload.');
}

public function userEdit($id)
{
    $dokumen = Dokumen::findOrFail($id);

    if ($dokumen->user_id != session('user_id')) {
        abort(403, 'Kamu tidak punya izin untuk mengedit dokumen ini.');
    }

    return view('user.upload-edit', compact('dokumen'));
}

public function userUpdate(Request $request, $id)
{
    $dokumen = Dokumen::findOrFail($id);

    if ($dokumen->user_id != session('user_id')) {
        abort(403, 'Kamu tidak punya izin untuk mengedit dokumen ini.');
    }

    $request->validate([
        'nama_dokumen' => 'required',
        'jenis' => 'required',
        'tahun' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:4096',
    ]);

    $data = $request->only(['nama_dokumen', 'jenis', 'tahun']);

    if ($request->hasFile('file')) {
        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }
        $data['file_path'] = $request->file('file')->store('dokumen', 'public');
    }

    $dokumen->update($data);

    return redirect()->route('user.upload.index')->with('success', 'Dokumen berhasil diperbarui.');
}

public function userDestroy($id)
{
    $dokumen = Dokumen::findOrFail($id);

    if ($dokumen->user_id != session('user_id')) {
        abort(403, 'Kamu tidak punya izin untuk menghapus dokumen ini.');
    }

    if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
        Storage::disk('public')->delete($dokumen->file_path);
    }

    $dokumen->delete();

    return redirect()->route('user.upload.index')->with('success', 'Dokumen berhasil dihapus.');
}

public function userDokumen(Request $request)
{
    $query = Dokumen::query();

    // 🔍 Fitur pencarian
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nama_dokumen', 'like', "%{$search}%")
              ->orWhere('tahun', 'like', "%{$search}%")
              ->orWhere('bidang', 'like', "%{$search}%");
        });
    }

    $dokumens = $query->orderBy('created_at', 'desc')->get();

    return view('user.dokumen', compact('dokumens'));
}


}