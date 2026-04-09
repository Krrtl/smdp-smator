@extends('layouts.app')

@section('title', 'Data Pelanggaran Siswa')

@section('content')
<div class="card shadow p-4">
    <style>
        .status-badge {
            padding: 6px 10px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
        }
        .status-belum { background-color: #dc3545; }
        .status-sudah { background-color: #28a745; }
        .keterangan-sanksi, .keterangan-form {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 6px;
            margin-top: 6px;
            font-size: 0.85rem;
            color: #333;
        }
        .keterangan-form textarea {
            width: 100%;
            resize: vertical;
            margin-bottom: 4px;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0"><i class="bi bi-exclamation-triangle"></i> Data Pelanggaran Siswa</h4>
        <a href="{{ route('pelanggaran.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-square"></i> Tambahkan Pelanggaran
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center upload-table">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Skor</th>
                    <th>Status Sanksi</th>
                    <th>Detail Sanksi / Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggarans as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->tanggal ? $p->tanggal->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $p->kelas }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->jenis }}</td>
                        <td>{{ $p->skor }}</td>
                        <td>
                            @if($p->status_sanksi)
                                <span class="status-badge status-sudah">Sudah</span>
                            @else
                                <span class="status-badge status-belum">Belum</span>
                            @endif
                        </td>

                        <td class="text-start">
                            @if($p->status_sanksi)
                                <div class="keterangan-sanksi d-none">
                                    {{ $p->keterangan ?? 'Belum ada catatan.' }}
                                </div>

                                <!-- Form edit keterangan -->
                                <form action="{{ route('pelanggaran.updateKeterangan', $p->id) }}" method="POST" class="keterangan-form d-none">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="keterangan" rows="2" placeholder="Tulis catatan sanksi...">{{ $p->keterangan }}</textarea>
                                    <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                                    <button type="button" class="btn btn-sm btn-secondary btn-cancel">Batal</button>
                                </form>

                                <button class="btn btn-sm btn-info btn-detail mt-1">Detail / Edit</button>
                            @else
                                <span class="text-muted">Belum ada sanksi</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                                @if(!$p->status_sanksi)
                                <form action="{{ route('pelanggaran.toggle', $p->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Tandai Selesai
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('pelanggaran.destroy', $p->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin hapus data ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted">Belum ada data pelanggaran</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function() {
            const cell = this.closest('td');
            const keterangan = cell.querySelector('.keterangan-sanksi');
            const form = cell.querySelector('.keterangan-form');

            if(keterangan.classList.contains('d-none') && form.classList.contains('d-none')) {
                keterangan.classList.remove('d-none'); // tampilkan teks keterangan
                form.classList.remove('d-none');       // tampilkan form edit
                this.textContent = 'Tutup';
            } else {
                keterangan.classList.add('d-none');
                form.classList.add('d-none');
                this.textContent = 'Detail / Edit';
            }
        });
    });

    // Tombol cancel di form
    document.querySelectorAll('.btn-cancel').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.keterangan-form');
            const cell = this.closest('td');
            const detailBtn = cell.querySelector('.btn-detail');
            form.classList.add('d-none');
            const keterangan = cell.querySelector('.keterangan-sanksi');
            keterangan.classList.add('d-none');
            detailBtn.textContent = 'Detail / Edit';
        });
    });
</script>
@endsection
