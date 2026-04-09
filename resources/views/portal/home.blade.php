@extends('portal.layout')

@section('title', 'Beranda')

@section('content')
<style>
    /* ===== Hero Section ===== */
    .hero-section {
        background: url('{{ asset("images/smator-bg.png") }}') no-repeat center center/cover;
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        overflow: hidden;
    }

    /* Overlay gradasi gelap */
    .hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8));
        backdrop-filter: blur(3px);
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        padding: 50px 20px;
        animation: fadeInUp 1.3s ease-out;
    }

    .hero-content h1 {
        font-size: 2.7rem;
        line-height: 1.4;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .hero-content p {
        font-size: 1.2rem;
        font-weight: 400;
        color: #e6e6e6;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2rem;
        }
        .hero-content p {
            font-size: 1rem;
        }
    }
</style>

<!-- ===== Hero Section ===== -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Sistem Informasi Manajemen Sekolah<br>Mengelola Dokumen dan Pelanggaran Siswa secara Efisien dan Terintegritas</h1>
        <p>Silakan pilih menu untuk mulai mengelola dokumen sekolah.</p>
    </div>
</section>
@endsection
