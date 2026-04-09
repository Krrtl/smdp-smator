@extends('layouts.app')

@section('title', 'Arsip Siswa')

@section('content')

<style>
    .pagination .page-link {
        padding: 0.35rem 0.75rem;
        font-size: 0.9rem;
        line-height: 1;
        border-radius: 8px;
    }

    .pagination .page-item .page-link i {
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .pagination {
        justify-content: center;
        margin-top: 1rem;
    }

    /* Hilangkan panah svg default Laravel */
    .pagination svg,
    .pagination span {
        display: none !important;
    }

    .table-responsive-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        margin-bottom: 1rem;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive-wrapper table {
        min-width: 600px;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .table-responsive-wrapper table {
            font-size: 0.85rem;
        }
        .table-responsive-wrapper th,
        .table-responsive-wrapper td {
            padding: 0.5rem !important;
        }
        .hide-on-mobile {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .table-responsive-wrapper table {
            font-size: 0.78rem;
        }
        .table-responsive-wrapper th,
        .table-responsive-wrapper td {
            padding: 0.4rem 0.3rem !important;
        }
    }
</style>

<div class="card shadow p-4">
    <h4 class="fw-semibold mb-3"><i class="bi bi-archive"></i> Arsip Siswa</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol kembali --}}
    <a href="{{ route('siswa.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
    </a>

    {{-- Filter --}}
    <form method="GET" action="{{ route('siswa.arsip') }}" class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari nama siswa..." value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>
    </form>

    {{-- Tabel arsip --}}
    <div class="table-responsive-wrapper">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th class="hide-on-mobile">Kelas Terakhir</th>
                    <th>Status</th>
                    <th class="hide-on-mobile">Tahun Update</th>
                    <th>Aktif</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $index => $s)
                <tr>
                    <td>{{ $siswa->firstItem() + $index }}</td>
                    <td>{{ $s->nama }}</td>
                    <td class="hide-on-mobile">{{ $s->kelas }}</td>
                    <td>
                        <span class="badge bg-{{ $s->status == 'lulus' ? 'success' : 'danger' }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>
                    <td class="hide-on-mobile">{{ $s->tahun_update }}</td>
                    <td>
                        <span class="badge bg-{{ $s->is_active ? 'success' : 'secondary' }}">
                            {{ $s->is_active ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data arsip siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    {{ $siswa->appends(request()->query())->links() }}

</div>

@endsection
