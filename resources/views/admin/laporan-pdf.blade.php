<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan Dokumen</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        h2, h4 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        .user-header { background-color: #e8f4f8; font-weight: bold; }
        .detail-row { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        .summary { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Bulanan Upload Dokumen</h2>
    <h4>Bulan: {{ $namaBulan }} {{ $tahun }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Bidang</th>
                <th>Judul Dokumen</th>
                <th>Jenis</th>
                <th>Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($laporan as $userData)
                @php
                    $user = $userData->user;
                    $dokumenList = $userData->dokumen ?? collect();
                    $bidangUser = $dokumenList->first() ? $dokumenList->first()->bidang : ($user->jabatan ?? '-');
                    // Format nama yang lebih singkat
                    $namaUser = $user->nama ?? 'Tidak Diketahui';
                    if (strpos($namaUser, 'Mohamad Tofan') !== false) {
                        $namaUser = 'Tofan';
                    } elseif (strpos($namaUser, 'Sayyimin') !== false) {
                        $namaUser = 'Sayimin';
                    }
                    $firstRow = true;
                @endphp

                @if($dokumenList->count() > 0)
                    @foreach($dokumenList as $dokumen)
                    <tr class="{{ $firstRow ? 'user-header' : 'detail-row' }}">
                        @if($firstRow)
                            <td rowspan="{{ $dokumenList->count() }}">{{ $no++ }}</td>
                            <td rowspan="{{ $dokumenList->count() }}">{{ $namaUser }}</td>
                            <td rowspan="{{ $dokumenList->count() }}">{{ $bidangUser }}</td>
                        @endif
                        <td>{{ $dokumen->nama_dokumen }}</td>
                        <td>{{ $dokumen->jenis }}</td>
                        <td>{{ $dokumen->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @php $firstRow = false; @endphp
                    @endforeach
                @else
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $namaUser }}</td>
                        <td>{{ $bidangUser }}</td>
                        <td colspan="3">Tidak ada dokumen</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6">Tidak ada data upload bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p>Total User yang Upload: {{ $laporan->count() }}</p>
        <p>Total Dokumen: {{ $laporan->sum('total_upload') }}</p>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggalCetak }}</p>
    </div>
</body>
</html>
