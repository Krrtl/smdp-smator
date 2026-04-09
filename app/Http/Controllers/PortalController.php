<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggaran;

class PortalController extends Controller
{
    public function home()
    {
        return view('portal.home');
    }

    public function about()
    {
        return view('portal.about');
    }

   public function pelanggaran(Request $request)
{
    $query = Pelanggaran::query();

    if ($request->filled('kelas')) {
        $query->where('kelas', $request->kelas);
    }

    if ($request->filled('bulan')) {
        $query->whereYear('tanggal', substr($request->bulan, 0, 4))
            ->whereMonth('tanggal', substr($request->bulan, 5, 2));
    }

    if ($request->filled('nama')) {
        $query->where('nama', 'like', '%' . $request->nama . '%');
    }

    $pelanggarans = $query->latest()->get();

    return view('portal.pelanggaran', compact('pelanggarans'));
}

}