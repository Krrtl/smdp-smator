<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\SiswaHistory;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class SiswaController extends Controller
{
    // 🧭 Menampilkan daftar semua siswa
  public function index(Request $request)
{
    // 🔹 Ambil kelas hanya dari siswa AKTIF
    $kelasSemua = Siswa::select('kelas')
    ->distinct()
    ->orderBy('kelas')
    ->pluck('kelas');


    // 🔹 Query dasar (HANYA siswa aktif)
    $query = Siswa::where('is_active', true)
        ->orderBy('kelas')
        ->orderBy('nama');

    // 🔍 Filter Search
    if ($request->search) {
        $query->where('nama', 'LIKE', '%' . $request->search . '%');
    }

    // 🎒 Filter Kelas
    if ($request->kelas_filter) {
        $query->where('kelas', $request->kelas_filter);
    }

    // 📄 Pagination
    $siswa = $query->paginate(20)->withQueryString();

    // 📊 Statistik (SEMUA DATA, termasuk arsip)
    $totalAktif = Siswa::where('is_active', true)->count();
$totalLulus = Siswa::where('status', 'lulus')->where('is_active', false)->count();
$totalDropout = Siswa::where('status', 'keluar')->where('is_active', false)->count();

    return view('admin.siswa.index', compact(
        'siswa',
        'kelasSemua',
        'totalAktif',
        'totalLulus',
        'totalDropout'
    ));
}

    // 🧾 Menampilkan form upload Excel
    public function showImportForm()
    {
        return view('admin.siswa.import');
    }

    // 📤 Proses upload dan import Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048', // ✅ batas ukuran file 2MB
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
        } catch (\Exception $e) {
            // ✅ Jika file error, tampilkan pesan jelas
            return back()->withErrors(['file' => 'Terjadi kesalahan saat memproses file: ' . $e->getMessage()]);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimpor!');
    }

    // 📚 Naikan siswa kelas XI ke XII (otomatis, akhir tahun akademik)
    public function promoteClass(Request $request)
{
    $tahunSekarang = now()->year;

    // ⛔ AMBIL HANYA KELAS XI (untuk naik ke XII)
    $siswa = Siswa::where('is_active', true)
        ->where('status', 'aktif')
        ->where('kelas', 'LIKE', 'XI-%')
        ->get();

    $dinaikan = 0;

    foreach ($siswa as $s) {
        // XI -> XII (kelas tetap sama, hanya tingkat naik)
        $nomor = substr($s->kelas, 3);
        $kelas_baru = 'XII-' . $nomor;

        $this->recordHistory($s, $kelas_baru, null, $tahunSekarang, $request->user()?->id);

        $s->update([
            'kelas' => $kelas_baru,
            'tahun_update' => $tahunSekarang,
        ]);

        $dinaikan++;
    }

    return redirect()->back()
        ->with('success', "$dinaikan siswa kelas XI berhasil naik ke kelas XII!");
}

    // 🎓 Tandai siswa kelas XII tahun lalu sebagai lulus
public function markGraduated()
{
    // 🔐 Pengaman 1: hanya admin
    if (session('user_role') !== 'admin') {
        abort(403, 'Akses ditolak');
    }

    $tahunSekarang = now()->year;
    $userId = session('user_id');

    // 🔎 Ambil siswa XII yang MASIH AKTIF
    $siswaXII = Siswa::where('kelas', 'LIKE', 'XII-%')
        ->where('status', 'aktif')
        ->where('is_active', true)
        ->get();

    // 🛑 Pengaman 2: kalau tidak ada data
    if ($siswaXII->isEmpty()) {
        return redirect()->back()
            ->with('success', 'Tidak ada siswa kelas XII aktif yang dapat diluluskan.');
    }

    $jumlah = 0;

    foreach ($siswaXII as $siswa) {

        // 🛑 Pengaman 3: double check per siswa
        if ($siswa->status !== 'aktif' || !$siswa->is_active) {
            continue;
        }

        // Simpan history
        $this->recordHistory(
            $siswa,
            null,
            'lulus',
            $tahunSekarang,
            $userId
        );

        // Update jadi arsip
        $siswa->update([
            'status'       => 'lulus',
            'is_active'    => false,
            'tahun_update' => $tahunSekarang,
        ]);

        $jumlah++;
    }

    return redirect()->back()
        ->with('success', "$jumlah siswa kelas XII berhasil diluluskan.");
}


    // 📝 Helper: Catat perubahan ke siswa_histories
    private function recordHistory($siswa, $kelas_baru, $status_baru, $tahun, $user_id)
    {
        SiswaHistory::create([
            'siswa_id' => $siswa->id,
            'kelas_lama' => $siswa->kelas,
            'kelas_baru' => $kelas_baru ?? $siswa->kelas,
            'status_lama' => $siswa->status,
            'status_baru' => $status_baru ?? $siswa->status,
            'tahun' => $tahun,
            'created_by' => $user_id,
        ]);
    }

  public function showManualPromoteForm(Request $request)
{
    // Untuk search & filter supaya bisa mirip index
    $search = $request->input('search');
    $filterKelas = $request->input('kelas_filter');

    $query = Siswa::where('status', 'aktif')
                  ->where('is_active', true);

    if ($filterKelas) {
        $query->where('kelas', $filterKelas);
    }

    if ($search) {
        $query->where('nama', 'like', '%' . $search . '%');
    }

    // Pagination agar mirip index
    $siswa = $query->orderBy('kelas')
               ->orderBy('nama')
               ->paginate(20)
               ->appends($request->only('search', 'kelas_filter'));


    // 🔥 Generate kelas otomatis dari database (untuk filter)
    $kelasTersedia = Siswa::select('kelas')
        ->distinct()
        ->orderBy('kelas')
        ->pluck('kelas');

    // 📚 Kelas tujuan manual (statis, bukan dari DB agar selalu ada pilihan)
    $kelasTujuan = [
        'XI-1', 'XI-2', 'XI-3', 'XI-4', 'XI-5', 'XI-6', 'XI-7', // 7 jurusan untuk XI
        'XII-1', 'XII-2', 'XII-3', 'XII-4', 'XII-5', 'XII-6', 'XII-7' // 7 jurusan untuk XII
    ];

    return view('admin.siswa.promote_manual', compact('siswa', 'kelasTersedia', 'kelasTujuan', 'search', 'filterKelas'));
}

public function submitManualPromote(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'kelas_baru' => 'required|string',
    ]);

    $siswa = Siswa::findOrFail($request->siswa_id);
    $tahunSekarang = now()->year;

    // Simpan history
    $this->recordHistory(
        $siswa,
        $request->kelas_baru,
        null,
        $tahunSekarang,
        $request->user()?->id
    );

    // Update data siswa
    $siswa->update([
        'kelas' => $request->kelas_baru,
        'tahun_update' => $tahunSekarang,
    ]);

    return redirect()->back()->with('success', "Siswa {$siswa->nama} berhasil dipindahkan ke kelas {$request->kelas_baru}!");
}

public function markDropout($id)
{
    $siswa = Siswa::findOrFail($id);
    $tahunSekarang = now()->year;

    // Simpan history
    $this->recordHistory(
        $siswa,
        null,
        'keluar',
        $tahunSekarang,
        session('user_id')
    );

    $siswa->update([
        'status'    => 'keluar',
        'is_active' => false,
        'tahun_update' => $tahunSekarang,
    ]);

    return redirect()->route('siswa.index')
        ->with('success', "Siswa {$siswa->nama} berhasil ditandai keluar.");
}


public function arsip(Request $request)
{
    $query = Siswa::where('is_active', false)
        ->orderBy('tahun_update', 'desc')
        ->orderBy('nama');

    // 🔍 Search nama
    if ($request->search) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    // 🎓 Filter status (lulus / keluar)
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $siswa = $query->paginate(20)->withQueryString();

    return view('admin.siswa.arsip', compact('siswa'));
}


}