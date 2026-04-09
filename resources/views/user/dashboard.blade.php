@extends('layouts.app')
@section('title', 'Dashboard User')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="card shadow-sm border-0 p-4 text-center" style="max-width: 600px; width: 100%;">
        <img src="{{ asset('images/logo-smator.png') }}" alt="Logo Sekolah" width="120" class="mx-auto mb-3">
        
        <h4 class="fw-semibold mb-3">
            Selamat Datang, <span class="text-primary">{{ session('user_name') }}</span>!
        </h4>

        <p class="text-muted mb-2">
            Anda login sebagai <strong>User</strong> SMAN 1 Torjun
        </p>

        <hr class="my-3">
        <p class="text-secondary">
            Sistem Manajemen Dokumen membantu Anda mengelola, melihat, dan mengunduh dokumen sekolah dengan mudah.
        </p>

        <div class="alert alert-info mt-3 w-50 mx-auto">
        <i class="bi bi-file-earmark-text"></i> Total Dokumen yang Anda Upload: <strong>{{ $totalDokumen }}</strong>
    </div>
    </div>
</div>
@endsection
