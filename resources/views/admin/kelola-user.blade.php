@extends('layouts.app')

@section('title', 'Kelola User-Admin')

@section('content')
<div class="card shadow p-4">
    <style>
        /* Small responsive tweaks for kelola user table */
        .kelola-table { font-size: 0.95rem; }
        .kelola-table th, .kelola-table td { vertical-align: middle; }
        @media (max-width: 576px) {
            .kelola-table { font-size: 10px; }
            .action-btn { width: 100%; padding: 1px 2px; }
            .card .d-flex { flex-direction: column; gap: 0.5rem; }
            .btn{ padding: 2px 4px; font-size: 10px;}
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0"><i class="bi bi-people"></i> Kelola User</h4>
        <a href="{{ route('kelola.user.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Tambahkan User
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center kelola-table">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Last Updated</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="text-start">{{ $user->nama }}</td>
                    <td class="text-start">{{ $user->jabatan }}</td>
                    <td class="text-start">{{ ucfirst($user->role) }}</td>
                    <td>{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>******</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <a href="{{ route('kelola.user.edit', $user->id) }}" class="btn btn-warning btn-sm action-btn"><i class="bi bi-pencil-square"></i> Edit</a>
                            <form action="{{ route('kelola.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm action-btn"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted">Belum ada user yang ditambahkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
