@extends('layouts.app')

@section('title', 'Dashboard-Admin')

@section('content')
<div class="container text-center mt-3">
    <h4 class="fw-semibold mb-4">
        Selamat Datang {{ session('user_name') }} <br> di Sistem Manajemen Dokumen
    </h4>

    {{-- <img src="{{ asset('images/logo-smator.png') }}" alt="Logo Sekolah" width="100" class="my-4"> --}}

    @php
        $colors = ['#5dade2', '#58d68d', '#f7dc6f', '#a569bd', '#ec7063', '#7fb3d5', '#82e0aa'];
    @endphp

    <!-- Grid container responsif -->
    <div class="row justify-content-START g-3">
        @foreach ($data as $bidang => $jumlah)
        <div class="col-6 col-sm-4 col-md-3 col-lg-3">
            <div class="card text-center border-0 shadow-sm h-100" style="background-color: {{ $colors[$loop->index] ?? '#ccc' }};">
                <div class="card-body text-white d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="fw-semibold">{{ $bidang }}</h6>
                        <h3>{{ $jumlah }}</h3>
                    </div>
                    <a href="{{ route('dashboard.detail', $bidang) }}" class="btn btn-light btn-sm mt-2 fw-semibold">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        @php
            $hasTata = false;
            foreach ($data as $k => $v) {
                $key = strtolower(trim($k));
                if ($key === 'tata tertib' || $key === 'tatatertib') { $hasTata = true; break; }
            }

        @endphp
        @if (! $hasTata)
        <div class="col-6 col-sm-4 col-md-3 col-lg-3">
            <div class="card text-center border-0 shadow-sm h-100" style="background-color: {{ $colors[count($data) % count($colors)] ?? '#f39c12' }};">
                <div class="card-body text-white d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="fw-semibold">Tata Tertib</h6>
                        <h3>0</h3>
                    </div>
                    <a href="{{ route('dashboard.detail', 'Tata Tertib') }}" class="btn btn-light btn-sm mt-2 fw-semibold">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
