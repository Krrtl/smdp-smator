@extends('layouts.app')

@section('title', 'Naik Kelas Manual')

@section('content')
<div class="card shadow p-4">
    <h4 class="fw-semibold mb-3">📌 Naik Kelas Manual</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FILTER & SEARCH --}}
    <form method="GET" action="{{ route('siswa.promote.manual') }}" class="row g-3 mb-3">

        {{-- Filter Kelas --}}
        <div class="col-md-4">
            <label class="form-label fw-semibold">Filter Kelas</label>
            <select name="kelas_filter" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasTersedia as $k)
                    <option value="{{ $k }}" {{ $filterKelas == $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Search --}}
        <div class="col-md-4">
            <label class="form-label fw-semibold">Search Siswa</label>
            <input type="text" name="search" value="{{ $search }}" class="form-control"
                   placeholder="Cari nama siswa...">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Cari</button>
        </div>

    </form>


    {{-- TABEL SISWA --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>Nama</th>
                    <th>Kelas Sekarang</th>
                    <th>Pindah ke</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @foreach($siswa as $s)
               @php
    // Ambil awalan kelas (X, XI, XII)
    $prefix = Str::before($s->kelas, '-'); 
    $prefix = trim($prefix);

    // Kelas tujuan berdasarkan prefix
    if ($prefix === 'X') {
        $kelasNaik = collect($kelasTujuan)->filter(fn($k) => str_starts_with($k, 'XI'))->values()->all();
    } 
    elseif ($prefix === 'XI') {
        $kelasNaik = collect($kelasTujuan)->filter(fn($k) => str_starts_with($k, 'XII'))->values()->all();
    } 
    elseif ($prefix === 'XII') {
        // XII tidak naik, otomatis lulus
        $kelasNaik = ['LULUS'];
    } 
    else {
        $kelasNaik = [];
    }
@endphp


                <tr>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kelas }}</td>

                    <td>
                        <form method="POST" action="{{ route('siswa.promote.manual.submit') }}">
                            @csrf
                            <input type="hidden" name="siswa_id" value="{{ $s->id }}">

                            <select name="kelas_baru" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasNaik as $kelas)
                                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                                @endforeach
                            </select>
                    </td>

                    <td>
                            <button type="submit" class="btn btn-success btn-sm">
                                Pindahkan
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $siswa->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
