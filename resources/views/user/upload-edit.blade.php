@extends('layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
<div class="card shadow-sm p-4 mx-auto" style="max-width: 600px;">
    <h5 class="fw-bold mb-4 text-center">✏️ Edit Dokumen</h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.upload.update', $dokumen->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Dokumen</label>
            <input type="text" name="nama_dokumen" class="form-control" value="{{ $dokumen->nama_dokumen }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Dokumen</label>
            <select name="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis Dokumen --</option>
                <option value="Surat" {{ $dokumen->jenis == 'Surat' ? 'selected' : '' }}>Surat</option>
                <option value="Laporan" {{ $dokumen->jenis == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                <option value="Dokumen Lain" {{ $dokumen->jenis == 'Dokumen Lain' ? 'selected' : '' }}>Dokumen Lain</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <select name="tahun" class="form-select" required>
                <option value="">-- Pilih Tahun --</option>
                @for ($year = 2000; $year <= date('Y'); $year++)
                    <option value="{{ $year }}" {{ $dokumen->tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">File Dokumen</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Ukuran maksimal 4MB</small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('user.upload.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
            <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
