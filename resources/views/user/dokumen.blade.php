@extends('layouts.app')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="card shadow p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="fw-semibold mb-0"><i class="bi bi-folder2-open"></i>  Daftar Dokumen</h4>

        {{-- 🔍 Form Pencarian --}}
        <form action="{{ route('user.dokumen') }}" method="GET" class="d-flex flex-wrap" style="gap: 8px;">
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

    {{-- ✅ Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
 
    {{-- 📋 Tabel Dokumen --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-success">
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
                            <a href="{{ route('dokumen.view', $d->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                            <a href="{{ route('dokumen.download', $d->id) }}" class="btn btn-success btn-sm action-btn"><i class="bi bi-box-arrow-down"></i> Download</a>
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
