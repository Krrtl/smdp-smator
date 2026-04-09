<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Pelanggaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
   public function index(Request $request)
{
    $tipe = $request->get('tipe', 'dokumen'); // Default ke dokumen

    $userRole = $request->session()->get('user_role');
    $userJabatan = $request->session()->get('user_jabatan');
    $jabatanNorm = $userJabatan ? strtolower(str_replace(' ', '', $userJabatan)) : null;
    $isTatatertib = $userRole === 'tatatertib' || $jabatanNorm === 'tatatertib';

    if ($isTatatertib) {
        $tipe = 'pelanggaran';
    }

    if ($tipe === 'pelanggaran') {
        return $this->laporanPelanggaran($request);
    }

    $bulan = $request->get('bulan', date('m'));
    $tahun = $request->get('tahun', date('Y'));

    // Ambil data dokumen per user per bulan dengan detail
    $laporan = Dokumen::selectRaw('user_id, COUNT(*) as total_upload')
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->groupBy('user_id')
        ->with(['user'])
        ->get();

    // Untuk setiap user, ambil detail dokumen mereka
    foreach ($laporan as $item) {
        $item->dokumen = Dokumen::where('user_id', $item->user_id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at')
            ->get();
    }

    $poinMaksimal = 50; // Untuk konsistensi dengan laporan pelanggaran

    return view('admin.laporan', compact('laporan', 'bulan', 'tahun', 'tipe', 'poinMaksimal'));
}

    // 📊 Laporan Bulanan Pelanggaran
    public function laporanPelanggaran(Request $request)
    {
        try {
            $bulan = (int) $request->input('bulan', Carbon::now()->month);
            $tahun = (int) $request->input('tahun', Carbon::now()->year);

            // Ambil data pelanggaran per siswa per bulan
            $laporan = Pelanggaran::select('nama', 'kelas', DB::raw('SUM(skor) as total_skor'), DB::raw('COUNT(id) as jumlah_pelanggaran'))
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->groupBy('nama', 'kelas')
                ->orderBy('total_skor', 'desc')
                ->get();

            // Threshold poin maksimum (bisa disesuaikan)
            $poinMaksimal = 200;

            // Tambahkan status dan tindakan berdasarkan threshold
            foreach ($laporan as $item) {
                $totalPoin = $item->total_skor;

                if ($totalPoin <= 0) {
                    $item->status = 'Baik';
                    $item->tindakan = 'Tidak ada tindakan';
                } elseif ($totalPoin >= 1 && $totalPoin <= 40) {
                    $item->status = 'Aman';
                    $item->tindakan = 'Teguran tim tatib';
                } elseif ($totalPoin >= 41 && $totalPoin <= 80) {
                    $item->status = 'Waspada';
                    $item->tindakan = 'Teguran wali kelas + panggilan orang tua siswa';
                } elseif ($totalPoin >= 81 && $totalPoin <= 120) {
                    $item->status = 'Serius';
                    $item->tindakan = 'Teguran BK + skorsing';
                } elseif ($totalPoin >= 121 && $totalPoin <= 160) {
                    $item->status = 'Kritis';
                    $item->tindakan = 'Teguran waka kesiswaan (panggilan orang tua untuk pemindahan siswa)';
                } elseif ($totalPoin >= 161 && $totalPoin <= 200) {
                    $item->status = 'Bahaya';
                    $item->tindakan = 'Teguran kepala sekolah + panggilan orang tua untuk evaluasi serius';
                } else {
                    $item->status = 'Maksimum';
                    $item->tindakan = 'Teguran kepala sekolah (potensi rekomendasi pengeluaran)';
                }
            }

            // Untuk setiap siswa, ambil detail pelanggaran mereka
            foreach ($laporan as $item) {
                $item->detail_pelanggaran = Pelanggaran::where('nama', $item->nama)
                    ->where('kelas', $item->kelas)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->orderBy('tanggal', 'desc')
                    ->get();
            }

            $tipe = 'pelanggaran';

            return view('admin.laporan', compact('laporan', 'bulan', 'tahun', 'poinMaksimal', 'tipe'));
        } catch (\Exception $e) {
            Log::error('Error in laporanPelanggaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 🔹 Fungsi cetak PDF
   public function cetakPDF(Request $request)
{
    $bulan = $request->get('bulan', Carbon::now()->format('m'));
    $tahun = $request->get('tahun', Carbon::now()->format('Y'));

    // Ambil data dokumen per user per bulan dengan detail
    $laporan = Dokumen::selectRaw('user_id, COUNT(*) as total_upload')
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->groupBy('user_id')
        ->with(['user'])
        ->get();

    // Untuk setiap user, ambil detail dokumen mereka
    foreach ($laporan as $item) {
        $item->dokumen = Dokumen::where('user_id', $item->user_id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at')
            ->get();
    }

    // Human-readable month name to display in the PDF
    $namaBulan = Carbon::createFromFormat('m', $bulan)->format('F');
    // Printable timestamp
    $tanggalCetak = Carbon::now()->format('d F Y H:i');

    $pdf = Pdf::loadView('admin.laporan-pdf', compact('laporan', 'bulan', 'tahun', 'namaBulan', 'tanggalCetak'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream("Laporan_Dokumen_{$bulan}_{$tahun}.pdf");
}

}