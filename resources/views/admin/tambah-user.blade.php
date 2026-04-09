@extends('layouts.app')

@section('title', 'Tambah User-Admin')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('kelola.user') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h4 class="fw-semibold mb-0">Tambahkan User</h4>
    </div>

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('kelola.user.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control form-control-lg rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control form-control-lg rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control form-control-lg rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan" class="form-select form-select-lg rounded-pill" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Kesiswaan">Kesiswaan</option>
                    <option value="Kurikulum">Kurikulum</option>
                    <option value="Sarana Prasarana">Sarana Prasarana</option>
                    <option value="Humas">Humas</option>
                    <option value="Tata Usaha">Tata Usaha</option>
                    <option value="BK">BK</option>
                    <option value="Tata Tertib">Tata Tertib</option>
                    <option value="Guru Pengajar">Guru Pengajar</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Role</label>
                <select name="role" class="form-select form-select-lg rounded-pill" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="tatatertib">Tatatertib</option>
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-semibold action-btn">
                   <i class="bi bi-check-circle-fill"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
