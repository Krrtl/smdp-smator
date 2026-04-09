@extends('portal.layout')

@section('title', 'Data Pelanggaran Siswa')

@section('content')
<style>
    body { background-color: #f5f8ff; }
    h3.fw-bold { color: #2f3e9e; }
    .card { border-radius: 12px; transition: all 0.3s ease; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.05); }
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; }
    .table thead { background-color: #f0f4ff; font-weight: 600; color: #344767; }
    .table td, .table th { vertical-align: middle !important; }
    .table tbody tr:hover { background-color: #f8faff; transition: 0.2s; }
    .keterangan { font-size: 0.85rem; color: #495057; }

    .legend-box {
    display: inline-block;
    width: 18px;
    height: 18px;
    border-radius: 4px;
    vertical-align: middle;
    margin-right: 6px;
    border: 1px solid #ccc;
}
.legend-text {
    font-size: 0.9rem;
    color: #333;
    vertical-align: middle;
}

</style>

<div class="container py-4">
    <h3 class="fw-bold mb-4 text-center">
        <i class="bi bi-exclamation-triangle"></i> Data Pelanggaran Siswa SMAN 1 Torjun
    </h3>

    <!-- Filter & Statistik -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3"><i class="bi bi-funnel"></i> Filter Data</h5>
                    <form method="GET" action="{{ route('portal.pelanggaran') }}">
                        <div class="mb-3">
                            <label class="form-label">Filter Kelas</label>
                            <select name="kelas" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($pelanggarans->unique('kelas') as $p)
                                    <option value="{{ $p->kelas }}" {{ request('kelas') == $p->kelas ? 'selected' : '' }}>{{ $p->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Filter Bulan</label>
                            <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Filter Nama Siswa</label>
                            <input type="text" name="nama" class="form-control" placeholder="Cari nama siswa..." value="{{ request('nama') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3"><i class="bi bi-bar-chart"></i> Statistik Pelanggaran</h5>
                    @php
                        $totalPelanggaran = $pelanggarans->count();
                        $totalSkor = $pelanggarans->sum('skor');
                        $siswaTerlibat = $pelanggarans->unique('nama')->count();
                        $rataSkor = $totalPelanggaran ? round($totalSkor / $totalPelanggaran, 1) : 0;
                    @endphp
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 rounded bg-stat-blue border">
                                <p class="mb-1 text-muted">Total Pelanggaran</p>
                                <h4 class="fw-bold text-primary">{{ $totalPelanggaran }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 rounded bg-stat-red border">
                                <p class="mb-1 text-muted">Total Skor</p>
                                <h4 class="fw-bold text-danger">{{ $totalSkor }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 rounded bg-stat-yellow border">
                                <p class="mb-1 text-muted">Siswa Terlibat</p>
                                <h4 class="fw-bold text-warning">{{ $siswaTerlibat }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 rounded bg-stat-green border">
                                <p class="mb-1 text-muted">Rata-rata Skor</p>
                                <h4 class="fw-bold text-success">{{ $rataSkor }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Pelanggaran per Kelas -->
   @php
    // Ambil semua kelas dari tabel siswa
    $semuaKelas = \App\Models\Siswa::pluck('kelas')->unique()->sort()->values();

    // Hitung pelanggaran per kelas, termasuk yang 0
    $pelanggaranCounts = $semuaKelas->map(function($kelas) use ($pelanggarans) {
        return $pelanggarans->where('kelas', $kelas)->count();
    });

    // Label untuk chart (semua kelas)
    $kelasLabels = $semuaKelas;
@endphp


    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3"><i class="bi bi-bar-chart-line"></i> Pelanggaran per Kelas</h5>
            <canvas id="pelanggaranChart" height="100"></canvas>

            <div class="text-center mt-3">
    <div class="d-inline-block me-4">
        <span class="legend-box" style="background-color: rgba(241, 196, 15, 0.7);"></span>
        <span class="legend-text">Kelas X</span>
    </div>
    <div class="d-inline-block me-4">
        <span class="legend-box" style="background-color: rgba(231, 76, 60, 0.7);"></span>
        <span class="legend-text">Kelas XI</span>
    </div>
    <div class="d-inline-block">
        <span class="legend-box" style="background-color: rgba(46, 204, 113, 0.7);"></span>
        <span class="legend-text">Kelas XII</span>
    </div>
</div>

        </div>
    </div>

    <!-- Tabel Pelanggaran Publik -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-semibold mb-3"><i class="bi bi-journal-text"></i> Data Pelanggaran Siswa</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Pelanggaran</th>
                            <th>Skor</th>
                            <th>Status Sanksi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggarans as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                            <td>{{ $p->kelas }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->jenis }}</td>
                            <td>{{ $p->skor }}</td>
                            <td>
                                @if($p->status_sanksi)
                                    <span class="badge bg-success">Sudah</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </td>
                            <td class="bg-light text-center align-middle">
                                @if($p->status_sanksi && $p->keterangan)
                                    {{ $p->keterangan }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">
                                <i class="bi bi-clipboard-x"></i><br>Belum ada data pelanggaran.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const kelasLabels = {!! json_encode($kelasLabels) !!};
    const pelanggaranCounts = {!! json_encode($pelanggaranCounts) !!};

    // Warna per angkatan
    const colors = kelasLabels.map(kelas => {
        if (kelas.toUpperCase().startsWith('XII')) {
            return 'rgba(46, 204, 113, 0.7)'; // Hijau untuk XII
        } else if (kelas.toUpperCase().startsWith('XI')) {
            return 'rgba(231, 76, 60, 0.7)';  // Merah untuk XI
        } else if (kelas.toUpperCase().startsWith('X')) {
            return 'rgba(241, 196, 15, 0.7)'; // Kuning untuk X
        } else {
            return 'rgba(52, 152, 219, 0.7)'; // Default (biru) kalau ada kelas lain
        }
    });

    const ctx = document.getElementById('pelanggaranChart').getContext('2d');
    const pelanggaranChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: kelasLabels,
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: pelanggaranCounts,
                backgroundColor: colors,
                borderColor: colors.map(c => c.replace('0.7', '1')),
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' pelanggaran';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: { display: true, text: 'Jumlah Pelanggaran' }
                },
                x: {
                    title: { display: true, text: 'Kelas' }
                }
            }
        }
    });
</script>


@endsection
