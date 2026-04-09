@extends('layouts.app')

@section('title', 'Edit User-Admin')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('kelola.user') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h4 class="fw-semibold mb-0">Edit User</h4>
    </div>

    <div class="card shadow-sm p-4">
        <form method="POST" action="{{ route('kelola.user.update', $user->id) }}">
            @csrf
            <input type="hidden" id="originalRole" value="{{ $user->role }}">

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control form-control-lg rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control form-control-lg rounded-pill" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" class="form-control form-control-lg rounded-pill">
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan" class="form-select form-select-lg rounded-pill" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Kesiswaan" {{ $user->jabatan == 'Kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                    <option value="Kurikulum" {{ $user->jabatan == 'Kurikulum' ? 'selected' : '' }}>Kurikulum</option>
                    <option value="Sarana Prasarana" {{ $user->jabatan == 'Sarana Prasarana' ? 'selected' : '' }}>Sarana Prasarana</option>
                    <option value="Humas" {{ $user->jabatan == 'Humas' ? 'selected' : '' }}>Humas</option>
                    <option value="Tata Usaha" {{ $user->jabatan == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                    <option value="BK" {{ $user->jabatan == 'BK' ? 'selected' : '' }}>BK</option>
                    <option value="Tata Tertib" {{ $user->jabatan == 'Tata Tertib' ? 'selected' : '' }}>Tata Tertib</option>
                    <option value="Guru Pengajar" {{ $user->jabatan == 'Guru Pengajar' ? 'selected' : '' }}>Guru Pengajar</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Role</label>
                <select name="role" class="form-select form-select-lg rounded-pill" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="tatatertib" {{ $user->role == 'tatatertib' ? 'selected' : '' }}>Tatatertib</option>
                </select>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-semibold">
                   <i class="bi bi-check-circle-fill"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const form = document.querySelector('form[action$="/update"]');
        if (!form) return;

        form.addEventListener('submit', function(e){
            const original = document.getElementById('originalRole')?.value || '';
            const selected = form.querySelector('select[name="role"]').value;

            // If role changed to admin or tatatertib, confirm
            if (selected !== original && (selected === 'admin' || selected === 'tatatertib')) {
                const ok = confirm('Anda akan mengubah role user menjadi "' + selected + '". Lanjutkan?');
                if (!ok) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    })();
</script>
@endpush
