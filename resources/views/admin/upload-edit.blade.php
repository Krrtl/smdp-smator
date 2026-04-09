@extends('layouts.app')

@section('title', 'Edit Dokumen-Admin')

@section('content')
<div class="card shadow-sm p-4 mx-auto" style="max-width: 600px;">
    <h5 class="fw-bold mb-4 text-center">✏️ Edit Dokumen</h5>

    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('upload.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Dokumen</label>
            <input type="text" name="nama_dokumen" class="form-control"
                   value="{{ $dokumen->nama_dokumen }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Dokumen</label>
            <select name="jenis" class="form-select" required>
                <option value="Surat" {{ $dokumen->jenis == 'Surat' ? 'selected' : '' }}>Surat</option>
                <option value="Laporan" {{ $dokumen->jenis == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                <option value="Dokumen Lain" {{ $dokumen->jenis == 'Dokumen Lain' ? 'selected' : '' }}>Dokumen Lain</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Tahun</label>
            <select name="tahun" class="form-select" required>
                @for ($year = date('Y'); $year >= 2000; $year--)
                    <option value="{{ $year }}" {{ $dokumen->tahun == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">File Dokumen (Opsional)</label>
            <input type="file" name="file" class="form-control"
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
            <small class="text-muted">Abaikan jika tidak ingin mengganti file.</small>

            @if ($dokumen->file_path)
                <div class="mt-2">
                    <a href="{{ route('dokumen.view', $dokumen->id) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="bi bi-eye-fill "></i> Lihat Dokumen Lama
                    </a>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('upload.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
