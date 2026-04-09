<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Portal SMAN 1 Torjun</title>

    <!-- ✅ Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            padding-top: 80px; /* biar konten gak ketutup navbar fixed-top */
        }

        /* ===== Navbar ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: background 0.3s ease-in-out;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            margin-right: 10px;
        }

        .nav-link {
            color: #000 !important;
            font-weight: 500;
            margin: 0 10px;
            transition: 0.2s ease-in-out;
        }

        .nav-link:hover {
            color: #00c851 !important;
            font-weight: 600;
        }

        .btn-login {
            background-color: #00c851;
            color: #fff !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 18px;
            transition: 0.3s ease;
        }

        .btn-login:hover {
            background-color: #009f42;
        }

        /* ===== Footer ===== */
        footer {
            text-align: center;
            padding: 20px 0;
            background-color: #fff;
            border-top: 2px solid #000;
            font-weight: 500;
            margin-top: auto; /* 🔥 kunci biar nempel di bawah */
        }

        /* ===== Animasi Global ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
        
        .card {
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('portal.home') }}">
                <img src="{{ asset('images/logo-smator.png') }}" alt="Logo" width="40">
                <strong>SMAN 1 TORJUN</strong>
            </a>
            <div class="ms-auto d-flex align-items-center">
                <a href="{{ route('portal.home') }}" class="nav-link">Beranda</a>
                <a href="{{ route('portal.about') }}" class="nav-link">About</a>
                <a href="{{ route('portal.pelanggaran') }}" class="nav-link">Pelanggaran</a>
                <a href="{{ route('login.form') }}" class="btn btn-login ms-3">Login</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <small>© {{ date('Y') }} <strong>SMAN 1 Torjun</strong>. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
