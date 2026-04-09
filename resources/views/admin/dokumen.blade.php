@extends('layouts.app')

@section('title', 'Daftar Dokumen-Admin')

@section('content')
<div class="card shadow p-4">
    <style>
        /* Responsive tweaks for dokumen table */
        .dokumen-table { font-size: 0.95rem; }
        .dokumen-table th, .dokumen-table td { vertical-align: middle; }
        @media (max-width: 576px) {
            .dokumen-table { font-size: 10px; }
            .dokumen-table .hide-xs { display: none; }
            .action-btn { width: 100%; margin-bottom: 6px; padding: 1px 2px; }
            .card .d-flex { flex-direction: column; gap: 0.5rem; }
            .search-form { gap: 6px; }
            .btn{ padding: 2px 4px; font-size: 10px;}
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0"><i class="bi bi-folder2-open"></i> Dokumen</h4>

        {{-- 🔍 Form Pencarian --}}
        <form action="{{ route('dokumen.index') }}" method="GET" class="d-flex search-form" style="gap: 8px;">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Cari dokumen..." 
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
 
    <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center dokumen-table">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Judul Dokumen</th>
                <th>Tahun</th>
                <th>Bidang</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dokumens as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->nama_dokumen }}</td>
                    <td>{{ $d->tahun }}</td>
                    <td>{{ $d->bidang }}</td>
                    <td>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                            <a href="{{ route('dokumen.view', $d->id) }}" class="btn btn-info btn-sm action-btn "><i class="bi bi-eye"></i> Lihat</a>
                            <a href="{{ route('dokumen.download', $d->id) }}" class="btn btn-success btn-sm action-btn"><i class="bi bi-box-arrow-down"></i> Download</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted">Tidak ada dokumen ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
