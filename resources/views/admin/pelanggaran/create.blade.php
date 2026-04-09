@extends('layouts.app')

@section('title', 'Tambah Pelanggaran')

@section('content')
<div class="card shadow p-4">
    <h4 class="fw-semibold mb-3"><i class="bi bi-plus-square"></i> Tambahkan Pelanggaran</h4>

    <form action="{{ route('pelanggaran.store') }}" method="POST" id="formPelanggaran">
        @csrf
        {{-- ✅ Tanggal --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal & Waktu</label>
            <input type="datetime-local" name="tanggal" class="form-control" required
                value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}">
        </div>

        {{-- ✅ Pilih Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select name="kelas" id="kelas" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                @endforeach
            </select>
        </div>

        {{-- ✅ Pilih Nama Siswa (otomatis sesuai kelas) --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Siswa</label>
            <select name="nama" id="nama" class="form-select" required disabled>
                <option value="">-- Pilih Kelas Dulu --</option>
            </select>
        </div>

        {{-- ✅ Pilih Jenis Pelanggaran (dengan skor bawaan) --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Pelanggaran</label>
            <select name="jenis" id="jenis" class="form-select" required>
    <option value="">-- Pilih Jenis Pelanggaran --</option>

    <optgroup label="Pelanggaran Ringan (5 Poin)">
        <option value="Baju dikeluarkan" data-skor="5">Baju dikeluarkan</option>
        <option value="Tidak memakai dasi" data-skor="5">Tidak memakai dasi</option>
        <option value="Tidak memakai kaos kaki" data-skor="5">Tidak memakai kaos kaki</option>
        <option value="Atribut tidak lengkap" data-skor="5">Atribut tidak lengkap</option>
        <option value="Sepatu tidak sesuai aturan" data-skor="5">Sepatu tidak sesuai aturan</option>
        <option value="Kuku panjang / dicat" data-skor="5">Kuku panjang / dicat</option>
        <option value="Tato" data-skor="5">Tato</option>
        <option value="Membuang sampah sembarangan" data-skor="5">Membuang sampah sembarangan</option>
        <option value="Terlambat" data-skor="5">Terlambat</option>
    </optgroup>

    <optgroup label="Pelanggaran Sedang (10 Poin)">
        <option value="Rambut gondrong" data-skor="10">Rambut gondrong / dicat</option>
        <option value="Tidak piket" data-skor="10">Tidak melaksanakan piket</option>
        <option value="Mencoret tembok" data-skor="10">Mencoret tembok/meja</option>
        <option value="Berbicara kasar" data-skor="10">Berbicara kasar</option>
        <option value="Tidak ikut upacara" data-skor="10">Tidak ikut upacara</option>
        <option value="Tidak memakai seragam lengkap (upacara)" data-skor="10">Tidak memakai seragam lengkap (upacara)</option>
        <option value="Alpa" data-skor="10">Alpa</option>
        <option value="Jajan saat pelajaran" data-skor="10">Jajan saat pelajaran</option>
    </optgroup>

    <optgroup label="Pelanggaran Berat (25 Poin)">
        <option value="Bullying" data-skor="25">Perundungan / Bullying</option>
        <option value="Bolos" data-skor="25">Bolos</option>
        <option value="Loncat pagar" data-skor="25">Loncat pagar</option>
        <option value="Memakai atribut tidak sesuai aturan" data-skor="25">Memakai atribut tidak sesuai aturan</option>
        <option value="Membuat onar" data-skor="25">Membuat onar</option>
        <option value="Membawa alat make-up" data-skor="25">Membawa alat make-up</option>
        <option value="Mengganggu KBM" data-skor="25">Mengganggu KBM</option>
    </optgroup>

    <optgroup label="Pelanggaran Sangat Berat (50 Poin)">
        <option value="Merokok" data-skor="50">Merokok</option>
        <option value="Berpacaran" data-skor="50">Berpacaran</option>
        <option value="Memalsukan surat izin" data-skor="50">Memalsukan surat izin</option>
        <option value="Berkelahi" data-skor="50">Berkelahi</option>
        <option value="Melawan guru" data-skor="50">Melawan guru</option>
        <option value="Menyalahgunakan uang sekolah" data-skor="50">Menyalahgunakan uang sekolah</option>
        <option value="Mengedarkan video pornografi/kekerasan" data-skor="50">Mengedarkan video pornografi/kekerasan</option>
        <option value="Merusak fasilitas" data-skor="50">Merusak fasilitas</option>
    </optgroup>

    <optgroup label="75 Poin">
        <option value="Berbohong" data-skor="75">Berbohong kepada guru</option>
    </optgroup>

    <optgroup label="100 Poin">
        <option value="Memalak" data-skor="100">Memalak</option>
        <option value="Judi" data-skor="100">Judi</option>
        <option value="Mencuri" data-skor="100">Mencuri</option>
    </optgroup>

    <optgroup label="150 Poin">
        <option value="Tawuran" data-skor="150">Tawuran</option>
        <option value="Minum miras" data-skor="150">Minum minuman keras</option>
        <option value="Membawa narkoba" data-skor="150">Membawa narkoba</option>
    </optgroup>

    <optgroup label="200 Poin (Maksimal)">
        <option value="Menggunakan narkoba" data-skor="200">Menggunakan narkoba</option>
        <option value="Mengedarkan narkoba" data-skor="200">Mengedarkan narkoba</option>
        <option value="Tindak pidana" data-skor="200">Tindak pidana</option>
    </optgroup>

</select>

        </div>

        {{-- ✅ Skor otomatis muncul dari jenis pelanggaran --}}
        <div class="mb-3">
            <label for="skor" class="form-label">Skor</label>
            <input type="number" name="skor" id="skor" class="form-control" readonly>
        </div>

        {{-- ✅ Tombol --}}
        <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Simpan
        </button>
        <a href="{{ route('pelanggaran.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

{{-- ✅ Script --}}
<script>
    // Data siswa berdasarkan kelas (ini dioper dari controller)
    const siswaData = @json($siswaData);

    const kelasSelect = document.getElementById('kelas');
    const namaSelect = document.getElementById('nama');
    const jenisSelect = document.getElementById('jenis');
    const skorInput = document.getElementById('skor');

    // 🔹 Ubah daftar siswa sesuai kelas yang dipilih
    kelasSelect.addEventListener('change', function() {
        const kelas = this.value;
        namaSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
        namaSelect.disabled = true;

        if (kelas && siswaData[kelas]) {
            siswaData[kelas].forEach(nama => {
                const opt = document.createElement('option');
                opt.value = nama;
                opt.textContent = nama;
                namaSelect.appendChild(opt);
            });
            namaSelect.disabled = false;
        }
    });

    // 🔹 Otomatis isi skor berdasarkan jenis pelanggaran
    jenisSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const skor = selected.getAttribute('data-skor');
        skorInput.value = skor ? skor : '';
    });
</script>
@endsection
