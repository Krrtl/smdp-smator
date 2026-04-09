@extends('layouts.app')

@section('title', 'Tambah Dokumen')

@section('content')
<div class="card shadow-sm p-4 mx-auto" style="max-width: 600px;">
    <h5 class="fw-bold mb-4 text-center">➕ Tambah Dokumen Baru</h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.upload.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Dokumen</label>
            <input type="text" name="nama_dokumen" class="form-control" placeholder="Masukkan nama dokumen" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Dokumen</label>
            <select name="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis Dokumen --</option>
                <option value="Surat">Surat</option>
                <option value="Laporan">Laporan</option>
                <option value="Dokumen Lain">Dokumen Lain</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <select name="tahun" class="form-select" required>
                <option value="">-- Pilih Tahun --</option>
                @for ($year = 2000; $year <= date('Y'); $year++)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">File Dokumen</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" required>
            <small class="text-muted">Ukuran maksimal 4MB</small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('user.upload.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
            <button type="submit" class="btn btn-primary">🚀 Upload Dokumen</button>
        </div>
    </form>
</div>
@endsection
