@extends('layouts.app')

@section('title', 'Import Data Siswa')

@section('content')
<div class="card shadow p-4">
    <h4 class="fw-semibold mb-3"><i class="bi bi-upload"></i> Import Data Siswa dari Excel / CSV</h4>

    {{--  Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{--  Pesan error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{--  Form upload --}}
    <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label fw-semibold">Pilih File Excel/CSV</label>
            <input type="file" name="file" id="file" class="form-control" required>
            <small class="text-muted">
                Format yang didukung: <strong>.xlsx, .xls, .csv</strong><br>
                <strong>Pastikan kolom: nama | kelas</strong>
            </small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Import Data
            </button>
        </div>
    </form>
</div>
@endsection
