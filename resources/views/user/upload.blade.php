@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')

<div class="card shadow p-4">

    {{-- CSS responsif agar sama seperti admin --}}
    <style>
        .upload-table { font-size: 0.95rem; }
        .upload-table th, .upload-table td { vertical-align: middle; }

        @media (max-width: 576px) {
            .upload-table { font-size: 10px; }
            .action-btn { width: 100%; margin-bottom: 6px; }
            .card .d-flex { flex-direction: column; gap: 0.5rem; }
            .btn { padding: 2px 4px; font-size: 10px; }
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">
            <i class="bi bi-upload"></i> Upload Dokumen Saya
        </h4>

        <a href="{{ route('upload.create') }}" class="btn btn-primary">
            <i class="bi bi-file-earmark-plus"></i> Tambah Dokumen
        </a>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center upload-table">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Judul Dokumen</th>
                    <th>Tahun</th>
                    <th>Bidang</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($dokumens as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start text-break">{{ $d->nama_dokumen }}</td>
                    <td>{{ $d->tahun }}</td>
                    <td>{{ $d->bidang }}</td>

                    <td>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">

                            <a href="{{ route('upload.edit', $d->id) }}"
                            class="btn btn-warning btn-sm action-btn">
                                <i class="bi bi-pencil"></i> Edit
                            </a>

                            <form action="{{ route('upload.destroy', $d->id) }}"
                                method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm action-btn"
                                        onclick="return confirm('Yakin ingin hapus dokumen ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-muted">Belum ada dokumen yang Anda upload.</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection