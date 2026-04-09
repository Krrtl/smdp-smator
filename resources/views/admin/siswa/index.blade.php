@extends('layouts.app')

@section('title', 'Daftar Siswa')

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

    /* Table wrapper */
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
        .btn-sm {
            padding: 0.25rem 0.45rem;
            font-size: 0.75rem;
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
        .hide-on-mobile {
            display: none;
        }
    }
</style>

<div class="card shadow p-4">
    <h4 class="fw-semibold mb-3"><i class="bi bi-people"></i> Daftar Siswa</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol import --}}
    <a href="{{ route('siswa.import.form') }}" class="btn btn-primary mb-3">
        <i class="bi bi-upload"></i> Import Data Siswa
    </a>

    {{-- Tombol arsip --}}
    <a href="{{ route('siswa.arsip') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-archive"></i> Lihat Arsip Siswa
    </a>

    {{-- Batch actions admin --}}
    @if(session('user_role') === 'admin')
<div class="d-flex flex-wrap gap-2 mb-3">

    <!-- Naik Kelas -->
    <button class="btn btn-warning btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalNaikKelas">
        <i class="bi bi-arrow-up-circle"></i> Naik Kelas
    </button>

    <!-- Tandai Kelulusan (Massal) -->
    <form method="POST" action="{{ route('siswa.graduate') }}">
        @csrf
        <button type="submit"
                class="btn btn-info btn-sm"
                onclick="return confirm('Tandai seluruh siswa kelas XII sebagai lulus?')">
            <i class="bi bi-mortarboard"></i> Tandai Kelulusan
        </button>
    </form>

</div>
@endif


    {{-- Filter + Search --}}
    <form method="GET" action="{{ route('siswa.index') }}" class="row g-3 mb-3">

        {{-- Search --}}
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari nama siswa..."
                   value="{{ request('search') }}">
        </div>

        {{-- Filter Kelas --}}
        <div class="col-md-3">
            <select name="kelas_filter" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasSemua as $k)
                    <option value="{{ $k }}" {{ request('kelas_filter') == $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol Cari --}}
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>

    </form>

    {{-- Table siswa --}}
    <div class="table-responsive-wrapper">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th class="hide-on-mobile">Kelas</th>
                    <th>Status</th>
                    <th class="hide-on-mobile">Tahun Update</th>
                    <th>Aktif</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($siswa as $index => $s)
                <tr>
                    <td>{{ $siswa->firstItem() + $index }}</td>
                    <td>{{ $s->nama }}</td>
                    <td class="hide-on-mobile">{{ $s->kelas }}</td>

                    <td>
                        <span class="badge 
                            {{ $s->status === 'aktif' ? 'bg-success' : 
                               ($s->status === 'lulus' ? 'bg-info' : 'bg-danger') }}">
                            {{ ucfirst($s->status) }}
                        </span>
                    </td>

                    <td class="hide-on-mobile">{{ $s->tahun_update ?? '-' }}</td>

                    <td>
                        <span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $s->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>

                    <td>

    {{-- Tombol Keluar --}}
    @if($s->status == 'aktif' && $s->is_active)
        <form action="{{ route('siswa.dropout', $s->id) }}"
              method="POST"
              style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger"
                onclick="return confirm('Tandai siswa keluar?')">
                Keluar
            </button>
        </form>
    @endif

</td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-muted py-3">Belum ada data siswa</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $siswa->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>


<!-- Modal Pilihan Naik Kelas -->
<div class="modal fade" id="modalNaikKelas">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h5 class="text-center fw-semibold mb-3">Pilih Metode Naik Kelas</h5>

            <!-- Otomatis (XI ke XII) -->
            <form method="POST" action="{{ route('siswa.promote') }}" class="mb-2">
                @csrf
                <button type="submit" class="btn btn-warning w-100"
                    onclick="return confirm('Yakin ingin menaikkan siswa kelas XI ke XII secara otomatis?')">
                   <i class="bi bi-arrow-repeat"></i> Naik Kelas Otomatis (XI -> XII)
                </button>
            </form>

            <!-- Manual (X ke XI) -->
            <a href="{{ route('siswa.promote.manual') }}" class="btn btn-primary w-100">
                <i class="bi bi-graph-up-arrow"></i> Naik Kelas Manual (X -> XI)
            </a>

        </div>
    </div>
</div>

@endsection


