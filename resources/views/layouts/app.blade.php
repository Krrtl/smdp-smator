<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Dokumen SMAN 1 TORJUN')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            margin: 0;
            overflow-x: hidden;
            background: linear-gradient(to bottom right, #f8f9fa, #e8f0fe);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* === HEADER === */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #ffffff;
            border-bottom: 2px solid #ddd;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
        }

        .header .left-section {
            display: flex;
            align-items: center;
        }

        .header img {
            width: 50px;
            margin-right: 10px;
        }

        .brand-title {
            font-weight: 600;
            font-size: 18px;
            color: #222;
            margin: 0;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 26px;
            color: #333;
            cursor: pointer;
        }

        /* === SIDEBAR === */
        .sidebar {
            background-color: #5a8dee;
            height: calc(100vh - 70px); /* tinggi layar dikurangi tinggi header */
            position: fixed;
            width: 220px;
            top: 70px; /* mulai di bawah header */
            left: 0;
            padding-top: 0px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 2px 0 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 1001;
            
        }

        .sidebar.hide {
            left: -220px;
        }

        .menu a {
            display: block;
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            transition: 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background-color: #3E6FD0;
        }

        .sidebar a {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    padding: 10px 15px;
    color: #fff;
    text-decoration: none;
    transition: background 0.3s;
}

.sidebar a i {
    font-size: 1.2rem;
}

.sidebar a.active {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}


        /* === LOGOUT === */
        .logout-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logout-btn {
            background-color: #d9534f;
            border: none;
            border-radius: 20px;
            color: white;
            padding: 10px;
            width: 150px;
            font-weight: 600;
            box-shadow: 0 3px 6px rgba(0,0,0,0.3);
            transition: 0.3s;
        }

        .logout-btn:hover {
            background-color: #b52b27;
        }

        /* === KONTEN === */
        .content {
            margin-left: 220px;
            padding: 90px 30px 120px; /* extra bottom padding so footer doesn't overlap */
            transition: all 0.3s ease;
            flex: 1 0 auto; /* allow content to grow and push footer down */
        }

        /* === OVERLAY UNTUK HP === */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            z-index: 900;
        }

        .overlay.show {
            display: block;
        }

        /* --- RESPONSIVE BREAKPOINTS --- */
        @media (max-width: 992px) {
            .menu-toggle {
                display: inline-block;
            }

            .sidebar {
                top: 0; /* biar nutup dari atas (menimpa header) */
                height: 100vh; /* biar penuh ke bawah */
                left: -220px;
                z-index: 1100; /* lebih tinggi dari header */
            }

            .sidebar.show {
                left: 0;
                box-shadow: 4px 0 10px rgba(0,0,0,0.3);
            }

            .content {
                margin-left: 0;
                padding: 90px 20px 30px;
            }

            .header img {
                width: 45px;
            }

            .brand-title {
                font-size: 16px;
            }

            /* Hide username text on very small screens, keep avatar */
            .dropdown .btn span { display: inline-block; }
            @media (max-width: 420px) {
                .dropdown .btn span { display: none; }
                .header { padding: 8px 12px; }
                .brand-title { font-size: 14px; }
            }
            h4{
                font-size: 12px
            }
        }

        /* Additional small-screen improvements */
        @media (max-width: 576px) {
            /* Make action buttons stack and fill width */
            .action-btn { width: 100% !important; display: block; }

            /* Make primary content padding tighter */
            .content { padding: 80px 12px 20px; }

            /* Make cards and tables easier to read on small screens */
            .card { padding: 0.8rem; }

            /* Make sidebar links larger tappable targets */
            .sidebar a { padding: 12px 16px; font-size: 15px; }

            /* Make logout button fit */
            .logout-btn { width: 100%; }
        }

        /* Improve small-table readability by reducing font-size and wrapping */
        @media (max-width: 768px) {
            table { font-size: 0.92rem; }
            .kelola-table th, .kelola-table td { white-space: normal; }
        }

        /* === SMOOTH SCROLL FOR MOBILE === */
        html {
            scroll-behavior: smooth;
        }
        /* ===== Footer ===== */
        footer {
            text-align: center;
            padding: 16px 0;
            background-color: #fff;
            border-top: 1px solid #e6e6e6;
            font-weight: 500;
            margin-top: 24px;
            position: relative;
            margin-left: 220px;
            width: calc(100% - 220px);
        }

        /* footer full-width on smaller screens where sidebar collapses */
        @media (max-width: 992px) {
            footer { margin-left: 0; width: 100%; }
        }

    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="left-section">
        <button class="menu-toggle me-2" id="menuToggle">☰</button>
        <img src="{{ asset('images/logo-smator.png') }}" alt="Logo Sekolah">
        <h4 class="mb-0">Sistem Manajemen Dokumen SMAN 1 TORJUN</h4>
    </div>

    <!-- DROPDOWN USER -->
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('images/user.jpg') }}" alt="User" class="rounded-circle me-2" style="width: 30px">
            <span>{{ session('user_name') ?? 'User' }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li class="dropdown-item text-muted">Role: {{ session('user_role') ?? '-' }}</li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('password.form') }}"><i class="bi bi-gear"></i> Ubah Password</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i>
 Logout</button>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="menu">
        @if(session('user_role') === 'admin')
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i> Dashboard
</a>
<a href="{{ route('kelola.user') }}" class="{{ request()->is('kelola-user*') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Kelola User
</a>
<a href="{{ route('upload.index') }}" class="{{ request()->is('upload*') ? 'active' : '' }}">
    <i class="bi bi-upload"></i> Upload Dokumen
</a>
<a href="{{ route('dokumen.index') }}" class="{{ request()->is('dokumen*') ? 'active' : '' }}">
    <i class="bi bi-folder2-open"></i> Dokumen
</a>
<a href="{{ route('pelanggaran.index') }}" class="{{ request()->is('pelanggaran*') ? 'active' : '' }}">
    <i class="bi bi-exclamation-triangle"></i> Pelanggaran
</a>
<a href="{{ route('siswa.index') }}" class="{{ request()->is('siswa*') ? 'active' : '' }}">
    <i class="bi bi-person-lines-fill"></i> Data Siswa
</a>
<a href="{{ route('laporan.index') }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line"></i> Laporan
</a>

   
        @else
            <a href="{{ route('user.dashboard') }}" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">  <i class="bi bi-house-door"></i> Dashboard </a>
            <a href="{{ route('user.upload.index') }}" class="{{ request()->is('user/upload') ? 'active' : '' }}"><i class="bi bi-upload"></i> Upload Dokumen
</a>
            <a href="{{ route('user.dokumen') }}" class="{{ request()->is('user/dokumen') ? 'active' : '' }}"><i class="bi bi-folder2-open"></i> Dokumen
</a>
            @php
                $userRole = session('user_role');
                $userJabatan = session('user_jabatan');
                $jabatanNorm = $userJabatan ? strtolower(str_replace(' ', '', $userJabatan)) : null;
            @endphp

            @if($userRole === 'tatatertib' || $jabatanNorm === 'tatatertib')
                <a href="{{ route('pelanggaran.index') }}" class="{{ request()->is('pelanggaran*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i> Pelanggaran
                </a>
                <a href="{{ route('laporan.index', ['tipe' => 'pelanggaran']) }}" class="{{ request()->is('laporan*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i> Laporan
                </a>
            @endif
        @endif
    </div>

    <div class="logout-container">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>
</div>

<!-- OVERLAY UNTUK HP -->
<div class="overlay" id="overlay"></div>

<!-- KONTEN -->
<div class="content" id="content">
    @yield('content')
</div>

<!-- FOOTER -->
<footer>
    <small>© {{ date('Y') }} <strong>SMAN 1 Torjun</strong>. All rights reserved.</small>
</footer>

<!-- SCRIPT -->
<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
</script>

{{-- SCRIPT KHUSUS HALAMAN --}}
@yield('scripts')


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // Aktifkan select2
        $('#jenis').select2({
            placeholder: "Pilih atau ketik pelanggaran...",
            allowClear: true,
            width: '100%'
        });

        // Auto isi skor
        $('#jenis').on('change', function() {
            let skor = $(this).find(':selected').data('skor');
            $('#skor').val(skor ?? '');
        });

    });
</script>

</body>
</html>
