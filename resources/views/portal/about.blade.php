@extends('portal.layout')

@section('title', 'Tentang Sistem')

@section('content')
<style>
    /* ===== Hero Section ===== */
    .about-hero {
        background: linear-gradient(135deg, rgba(55, 62, 59, 0.85), rgba(51, 56, 53, 0.6)),
                    url('/images/smator-bg.png') no-repeat center center/cover;
        color: white;
        text-align: center;
        padding: 120px 20px 90px;
        position: relative;
        overflow: hidden;
    }

    .about-hero h1 {
        font-weight: 800;
        font-size: 2.6rem;
        margin-bottom: 10px;
        letter-spacing: 0.6px;
        animation: fadeInDown 1s ease-out;
    }

    .about-hero p {
        font-size: 1.05rem;
        max-width: 680px;
        margin: 0 auto;
        opacity: 0.97;
        animation: fadeInUp 1.3s ease-out;
        line-height: 1.6;
    }

    /* ===== Section: Tentang ===== */
    .about-content {
        padding: 90px 20px;
        background-color: #f9fafb;
    }

    .about-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        padding: 36px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 30px;
        animation: fadeInUp 1.2s ease-out;
        transition: transform 0.25s ease;
    }

    .about-card:hover {
        transform: translateY(-5px);
    }

    .about-text {
        flex: 1;
        min-width: 0;
    }

    .about-text h2 {
        font-weight: 700;
        margin-bottom: 18px;
        color: #1b1b1b;
        font-size: 1.6rem;
    }

    .about-text p {
        color: #444;
        line-height: 1.75;
        font-size: 1rem;
        text-align: justify;
        text-justify: inter-word;
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .about-text strong, .about-text em {
        color: #00803f;
    }

    .about-image {
        flex: 1;
        text-align: center;
        min-width: 0;
    }

    .about-image img {
        width: 100%;
        max-width: 420px;
        height: 260px;
        object-fit: cover;
        border-radius: 14px;
        box-shadow: 0 10px 18px rgba(0, 0, 0, 0.10);
        transition: transform 0.25s ease;
        max-width: 100%;
    }

    .about-image img:hover {
        transform: scale(1.05);
    }

    /* ===== Section: Fitur Utama ===== */
    .features {
        background: linear-gradient(180deg, #ffffff 0%, #f4fff6 100%);
        padding: 80px 20px;
        text-align: center;
    }

    .features h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1b1b1b;
        margin-bottom: 50px;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 12px;
    }

    .feature-card {
        background: #fff;
        padding: 30px 25px;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 28px rgba(0, 128, 64, 0.15);
    }

    .feature-card i {
        font-size: 2.5rem;
        color: #18e377;
        margin-bottom: 15px;
    }

    .feature-card h3 {
        font-size: 1.3rem;
        color: #222;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .feature-card p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* ===== Animations ===== */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Responsif ===== */
    @media (max-width: 768px) {
        .about-card {
            flex-direction: column;
            text-align: center;
            padding: 30px;
        }
        .about-text, .about-image {
            flex: 1 1 100%;
        }
        .about-hero h1 {
            font-size: 2.2rem;
        }
        .about-hero p {
            font-size: 1rem;
        }
        .about-image img {
            height: 220px;
            max-width: 320px;
            margin: 0 auto;
        }
        .about-text p { text-align: left; }
    }

    @media (max-width: 480px) {
        .about-hero { padding: 90px 14px 70px; }
        .about-hero h1 { font-size: 1.8rem; }
        .features h2 { font-size: 1.4rem; margin-bottom: 28px; }
        .feature-card { padding: 18px; }
        .feature-card h3 { font-size: 1.05rem; }
        .about-text h2 { font-size: 1.2rem; }
    }
</style>

<!-- ===== Hero Section ===== -->
<section class="about-hero">
    <h1>Tentang Sistem</h1>
    <p>Platform digital yang dirancang untuk meningkatkan efisiensi pengelolaan dokumen dan pelanggaran siswa di SMAN 1 Torjun.</p>
</section>

<!-- ===== About Content ===== -->
<section class="about-content">
    <div class="container">
        <div class="about-card">
            <div class="about-text">
                <h2>Apa itu Sistem Manajemen Dokumen?</h2>
                <p>
                    <strong>Sistem Manajemen Dokumen SMAN 1 Torjun</strong> adalah platform berbasis web yang membantu sekolah dalam menyimpan, 
                    mengatur, dan mengelola dokumen serta data pelanggaran siswa secara <strong>efisien</strong> dan <strong>terintegrasi</strong>. 
                    Sistem ini dikembangkan untuk mempermudah guru dan staf administrasi dalam proses pengarsipan, sekaligus 
                    mempercepat pencarian dokumen penting.
                </p>
                <p>
                    Tujuan utama sistem ini adalah menciptakan lingkungan kerja yang lebih <em>digital</em>, <em>transparan</em>, dan 
                    <em>ramah lingkungan</em> dengan mengurangi penggunaan kertas (<strong>paperless management</strong>).
                </p>
            </div>

            <div class="about-image">
    <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner rounded-3 shadow">
            <div class="carousel-item active">
                <img src="{{ asset('images/smator-bg.png') }}" class="d-block w-100 rounded-3" alt="Gedung SMAN 1 Torjun">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/smator-depan.webp') }}" class="d-block w-100 rounded-3" alt="Ruang Kelas SMAN 1 Torjun">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/smator-2.jpg') }}" class="d-block w-100 rounded-3" alt="Guru dan Staf SMAN 1 Torjun">
            </div>
        </div>

        <!-- Optional: tombol navigasi -->
        <button class="carousel-control-prev" type="button" data-bs-target="#aboutCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#aboutCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

        </div>
    </div>
</section>

<!-- ===== Fitur Utama ===== -->
<section class="features">
    <h2>Fitur Utama Sistem</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <i class="fas fa-folder-open"></i>
            <h3>Pengarsipan Digital</h3>
            <p>Dokumen sekolah tersimpan rapi dan mudah dicari melalui sistem berbasis web yang efisien.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-user-check"></i>
            <h3>Manajemen Pelanggaran</h3>
            <p>Data pelanggaran siswa tercatat secara otomatis, memudahkan pemantauan dan laporan.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-chart-line"></i>
            <h3>Laporan Otomatis</h3>
            <p>Sistem menghasilkan laporan dokumen dan pelanggaran secara cepat dan akurat.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-leaf"></i>
            <h3>Ramah Lingkungan</h3>
            <p>Mendukung upaya sekolah menuju lingkungan kerja <em>paperless</em> yang berkelanjutan.</p>
        </div>
        <div class="feature-card">
    <i class="fas fa-users-cog"></i>
    <h3>Akses Multi-User</h3>
    <p>Sistem mendukung berbagai peran pengguna seperti guru, staf, dan admin dengan hak akses berbeda untuk menjaga keamanan data.</p>
</div>
<div class="feature-card">
    <i class="fas fa-lock"></i>
    <h3>Keamanan Data Terjamin</h3>
    <p>Data dokumen dan pelanggaran siswa dilindungi dengan sistem autentikasi dan enkripsi agar tetap aman dan rahasia.</p>
</div>
    </div>
</section>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a2e8b6f5a7.js" crossorigin="anonymous"></script>
@endsection
