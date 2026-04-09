@extends('layouts.app')

@section('title', "Detail Bidang $jabatan")

@section('content')
<style>
    /* Responsive Table */
    .table-responsive-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .table-responsive-wrapper table {
        min-width: 500px;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .table-responsive-wrapper table {
            font-size: 0.85rem;
        }

        .table-responsive-wrapper th,
        .table-responsive-wrapper td {
            padding: 0.5rem !important;
        }
    }

    @media (max-width: 576px) {
        .table-responsive-wrapper table {
            font-size: 0.8rem;
        }

        .table-responsive-wrapper th,
        .table-responsive-wrapper td {
            padding: 0.4rem 0.25rem !important;
        }

        .hide-on-mobile {
            display: none;
        }
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h4 class="fw-semibold mb-0">Detail Bidang: {{ $jabatan }}</h4>
    </div>

    @if ($users->isEmpty())
        <div class="alert alert-warning text-center">
            Belum ada user di bidang ini.
        </div>
    @else
    <div class="card shadow-sm p-3">
        <div class="table-responsive-wrapper">
            <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif
</div>
@endsection
