@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="card shadow p-4 w-50 mx-auto">
    <h4 class="fw-semibold mb-3"><i class="bi bi-lock-fill"></i> Ubah Password</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Password Lama</label>
            <input type="password" name="password_lama" class="form-control" required>
            @error('password_lama') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" name="password_baru" class="form-control" required>
            @error('password_baru') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_baru_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Simpan Perubahan</button>
    </form>
</div>
@endsection
