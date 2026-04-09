@extends('layouts.app')

@php
    $role = session('user_role') ?? 'admin';
    $roleLabel = $role === 'tatatertib' ? 'Tatatertib' : ucfirst($role);
@endphp

@section('title', 'Laporan - ' . $roleLabel)

@section('content')
<div class="card shadow p-4">
    <style>
        /* Make laporan table font smaller for better fit on mobile */
        .laporan-table { font-size: 0.95rem; }
        .laporan-table th, .laporan-table td { vertical-align: middle; }
        @media (max-width: 576px) {
            .laporan-table { font-size: 10px; }
            .btn{ padding: 2px 4px; font-size: 10px;}
        }
    </style>

    @php
        $userRole = session('user_role');
        $userJabatan = session('user_jabatan');
        $jabatanNorm = $userJabatan ? strtolower(str_replace(' ', '', $userJabatan)) : null;
        $isTatatertib = $userRole === 'tatatertib' || $jabatanNorm === 'tatatertib';
    @endphp

    {{-- Navigasi Jenis Laporan --}}
    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group">
            @if(!$isTatatertib)
                <a href="{{ route('laporan.index', ['tipe' => 'dokumen']) }}" class="btn btn-outline-primary {{ $tipe === 'dokumen' ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Laporan Dokumen
                </a>
            @endif
            <a href="{{ route('laporan.index', ['tipe' => 'pelanggaran']) }}" class="btn btn-outline-danger {{ $tipe === 'pelanggaran' ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i> Laporan Pelanggaran
            </a>
        </div>
    </div>

    @if($tipe === 'dokumen')
        {{-- LAPORAN DOKUMEN --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
            <h4 class="fw-semibold mb-2 mb-sm-0"><i class="bi bi-file-earmark-text"></i> Laporan Upload Dokumen Bulanan</h4>
            <a href="{{ route('laporan.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success ms-sm-3">
                <i class="bi bi-printer-fill"></i> Cetak PDF
            </a>
        </div>

        {{-- Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-3">
            <input type="hidden" name="tipe" value="dokumen">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun</label>
                <select name="tahun" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center laporan-table">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Nama Pengunggah</th>
                    <th>Bidang</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Total Upload</th>
                    <th>Detail Dokumen</th>
                </tr>
            </thead>
           <tbody>
@forelse ($laporan as $index => $l)
    @php
        $user = $l->user;
        $dokumenUser = $l->dokumen ?? collect();
        $bidangUser = $dokumenUser->first()
            ? $dokumenUser->first()->bidang
            : ($user->jabatan ?? '-');

        $namaUser = $user->nama ?? 'Tidak Diketahui';
    @endphp

    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $namaUser }}</td>
        <td>{{ $bidangUser }}</td>
        <td>{{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }}</td>
        <td>{{ $tahun }}</td>
        <td class="fw-semibold">{{ $l->total_upload }}</td>
        <td>
            <button class="btn btn-sm btn-info"
                data-bs-toggle="modal"
                data-bs-target="#modalDetail{{ $l->user_id }}">
                <i class="bi bi-eye"></i> Lihat
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-muted">
            Belum ada data laporan dokumen.
        </td>
    </tr>
@endforelse
</tbody>

        </table>
        {{--  MODAL DETAIL (DI LUAR TABLE) --}}
@foreach ($laporan as $l)
@php
    $dokumenUser = $l->dokumen ?? collect();
    $namaUser = $l->user->nama ?? '-';
@endphp

<div class="modal fade" id="modalDetail{{ $l->user_id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Detail Dokumen - {{ $namaUser }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if($dokumenUser->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-striped text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Tanggal Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dokumenUser as $idx => $doc)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $doc->nama_dokumen }}</td>
                                <td>{{ $doc->jenis ?? '-' }}</td>
                                <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-muted text-center">
                        Tidak ada dokumen.
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endforeach


        </div>

    @else
        {{-- LAPORAN PELANGGARAN --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
            <h4 class="fw-semibold mb-2 mb-sm-0"><i class="bi bi-exclamation-triangle"></i>  Laporan Bulanan Pelanggaran</h4>
        </div>

        {{-- Filter Bulan & Tahun --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-3">
            <input type="hidden" name="tipe" value="pelanggaran">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun</label>
                <select name="tahun" class="form-select">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>

        {{-- Info Threshold --}}
        <div class="alert alert-info">
            <strong>Sistem Threshold Pelanggaran Siswa:</strong><br>
             <ul class="mb-0">
        <li>Aman (1–40): Teguran tim tatib</li>
        <li>Waspada (41–80): Teguran wali kelas + panggilan orang tua siswa</li>
        <li>Serius (81–120): Teguran BK + skorsing</li>
        <li>Kritis (121–160): Teguran waka kesiswaan + panggilan orang tua untuk evaluasi lebih lanjut</li>
        <li>Bahaya (161–200): Teguran kepala sekolah + panggilan orang tua untuk tindakan serius</li>
        <li>Maksimum (&gt;200): Potensi rekomendasi pengeluaran</li>
    </ul>
        </div>

        {{-- Tabel Laporan --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle laporan-table">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jumlah Pelanggaran</th>
                        <th>Total Skor</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                        <th>Detail Pelanggaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kelas }}</td>
                        <td>{{ $item->jumlah_pelanggaran }}</td>
                        <td>
                            <span class="badge bg-{{ $item->total_skor >= 100 ? 'danger' : ($item->total_skor >= 50 ? 'warning' : 'success') }}">
                                {{ $item->total_skor }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'Maksimum' ? 'dark' : ($item->status == 'Kritis' ? 'danger' : ($item->status == 'Bahaya' ? 'warning' : ($item->status == 'Serius' ? 'info' : ($item->status == 'Waspada' ? 'secondary' : 'success')))) }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="text-start">
                            <small>{{ $item->tindakan }}</small>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPelanggaran{{ $index }}">
                                <i class="bi bi-exclamation-triangle"></i> Lihat
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Tidak ada data pelanggaran untuk bulan {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal Detail Pelanggaran --}}
        @foreach($laporan as $index => $item)
        <div class="modal fade" id="modalPelanggaran{{ $index }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pelanggaran - {{ $item->nama ?? 'N/A' }} ({{ $item->kelas ?? 'N/A' }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pelanggaran</th>
                                        <th>Skor</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($item->detail_pelanggaran) && $item->detail_pelanggaran->count() > 0)
                                        @foreach($item->detail_pelanggaran as $idx => $pelanggaran)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td>{{ $pelanggaran->jenis ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $pelanggaran->skor ?? 0 }}</span>
                                            </td>
                                            <td>{{ isset($pelanggaran->tanggal) ? $pelanggaran->tanggal->format('d/m/Y H:i') : 'N/A' }}</td>
                                            <td>{{ $pelanggaran->keterangan ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Tidak ada detail pelanggaran</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if(isset($item->detail_pelanggaran) && $item->detail_pelanggaran->count() > 0)
                        <div class="mt-3">
                            <strong>Ringkasan:</strong>
                            <ul>
                                <li>Total Pelanggaran: {{ $item->detail_pelanggaran->count() }}</li>
                                <li>Total Skor: {{ $item->detail_pelanggaran->sum('skor') }}</li>
                                <li>Status: <span class="badge bg-{{ ($item->status ?? 'Baik') == 'Maksimum' ? 'dark' : (($item->status ?? 'Baik') == 'Kritis' ? 'danger' : (($item->status ?? 'Baik') == 'Bahaya' ? 'warning' : (($item->status ?? 'Baik') == 'Serius' ? 'info' : (($item->status ?? 'Baik') == 'Waspada' ? 'secondary' : 'success')))) }}">{{ $item->status ?? 'Baik' }}</span></li>
                                <li>Tindakan: {{ $item->tindakan ?? 'Tidak ada tindakan' }}</li>
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        </table>
</div>


      {{-- Ringkasan --}}
@if($laporan->count() > 0)
<div class="mt-4">
    <h5>Ringkasan Bulan {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</h5>

    {{-- CARD RINGKASAN --}}
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <small class="text-muted">Total Siswa Melanggar</small>
                    <h3 class="mb-0">{{ $laporan->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-start border-4 border-warning">
                <div class="card-body">
                    <small class="text-muted">Total Pelanggaran</small>
                    <h3 class="mb-0">{{ $laporan->sum('jumlah_pelanggaran') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-start border-4 border-danger">
                <div class="card-body">
                    <small class="text-muted">Total Skor</small>
                    <h3 class="mb-0">{{ $laporan->sum('total_skor') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- CHART --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Distribusi Status Siswa</h6>
            <canvas id="statusChart" height="120"></canvas>
        </div>
    </div>
</div>
@endif


    @endif

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('statusChart');

    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Aman', 'Waspada', 'Serius', 'Kritis', 'Bahaya', 'Maksimum'],
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: [
                        {{ $laporan->where('status','Aman')->count() }},
                        {{ $laporan->where('status','Waspada')->count() }},
                        {{ $laporan->where('status','Serius')->count() }},
                        {{ $laporan->where('status','Kritis')->count() }},
                        {{ $laporan->where('status','Bahaya')->count() }},
                        {{ $laporan->where('status','Maksimum')->count() }}
                    ],
                    backgroundColor: [
                        '#198754',
                        '#ffc107',
                        '#0d6efd',
                        '#dc3545',
                        '#6c757d',
                        '#212529'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
</script>
@endsection
