<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // ✅ Cegah duplikasi nama + kelas
        if (!isset($row['nama']) || !isset($row['kelas'])) {
            return null; // skip baris kosong
        }

        $tahunSekarang = now()->year; // ✅ Define tahun sekarang di awal

        // Cek apakah sudah ada siswa dengan nama dan kelas sama
        $siswa = Siswa::where('nama', $row['nama'])->where('kelas', $row['kelas'])->first();

        if ($siswa) {
            // Jika sudah ada, update status dan tahun
            $siswa->update([
                'status' => 'aktif',
                'is_active' => true,
                'tahun_update' => $tahunSekarang,
            ]);
            return null; // skip agar tidak dobel
        }

        return new Siswa([
            'kelas' => $row['kelas'],
            'nama'  => $row['nama'],
            'status' => 'aktif',
            'tahun_angkatan' => $tahunSekarang,
            'tahun_update' => $tahunSekarang,
            'is_active' => true,
        ]);
    }

    public function rules(): array
    {
        // ✅ Tambah validasi tiap baris
        return [
            '*.nama' => 'required|string',
            '*.kelas' => 'required|string',
        ];
    }
}