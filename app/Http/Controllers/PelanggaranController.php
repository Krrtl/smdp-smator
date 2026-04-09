<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PelanggaranController extends Controller
{
    public function index()
    {
        $pelanggarans = Pelanggaran::latest()->get();
        return view('admin.pelanggaran.index', compact('pelanggarans'));
    }

   public function create()
{
    // ✅ Ambil semua siswa dari database
    $siswa = \App\Models\Siswa::all();

    // ✅ Buat daftar kelas unik
    $kelasList = $siswa->pluck('kelas')->unique()->sort()->values();

    // ✅ Susun data siswa berdasarkan kelas (untuk dropdown otomatis)
    $siswaData = [];
    foreach ($kelasList as $kelas) {
        $siswaData[$kelas] = $siswa->where('kelas', $kelas)->pluck('nama')->values();
    }

$pelanggaranData = \App\Models\Pelanggaran::select('kelas', DB::raw('COUNT(id) as total'))
    ->groupBy('kelas')
    ->pluck('total', 'kelas');


    // ✅ Pastikan semua kelas muncul di grafik (meskipun 0 pelanggaran)
    $dataGrafik = [];
    foreach ($kelasList as $kelas) {
        $dataGrafik[$kelas] = $pelanggaranData[$kelas] ?? 0;
    }

    // ✅ Siapkan data untuk chart
    $labels = array_keys($dataGrafik);
    $values = array_values($dataGrafik);

    // ✅ Kirim semua data ke view
    return view('admin.pelanggaran.create', compact('kelasList', 'siswaData', 'labels', 'values'));
}


    /**
     * Store a newly created pelanggaran in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kelas' => 'required|string',
            'nama' => 'required|string',
            'jenis' => 'required|string',
            'skor' => 'required|integer',
            'status_sanksi' => 'nullable|boolean',
            'keterangan' => 'nullable|string',
        ]);

        // default status_sanksi ke false jika tidak dikirim
        $validated['status_sanksi'] = $validated['status_sanksi'] ?? false;

        Pelanggaran::create($validated);

        return redirect()->route('pelanggaran.index')
                        ->with('success', 'Pelanggaran berhasil ditambahkan!');
    }

    /**
     * Toggle status_sanksi.
     */
    public function toggle($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->status_sanksi = !$pelanggaran->status_sanksi;
        $pelanggaran->save();

        return redirect()->back()
                        ->with('success', 'Status sanksi berhasil diperbarui!');
    }

    /**
     * Show details of a pelanggaran.
     */
    public function show($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        return view('admin.pelanggaran.show', compact('pelanggaran'));
    }

    /**
     * Delete a pelanggaran.
     */
    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->delete();

        return redirect()->back()
                        ->with('success', 'Data pelanggaran berhasil dihapus!');
    }

    public function updateKeterangan(Request $request, $id)
{
    $pelanggaran = Pelanggaran::findOrFail($id);

    $validated = $request->validate([
        'keterangan' => 'nullable|string',
    ]);

    $pelanggaran->keterangan = $validated['keterangan'];
    $pelanggaran->save();

    return redirect()->back()->with('success', 'Keterangan sanksi berhasil diperbarui!');
}

}