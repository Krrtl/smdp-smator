<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Dokumen Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* === Background dengan gambar lokal & efek blur === */
        body {
            background: url('{{ asset("images/smator-bg.png") }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
        }

        /* Lapisan blur transparan di atas gambar */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }

        /* Kotak login */
        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            padding: 36px;
            width: 420px;
            max-width: 92%;
            text-align: center;
            backdrop-filter: blur(3px);
        }

         .school-logo {
            width: 72px;
            height: 72px;
            margin-bottom: 20px;
        }

        h3 {
            margin-bottom: 12px;
            font-weight: 600;
            color: #000000;
            font-size: 18px;
        }

        label {
            font-weight: 500;
            text-align: left;
            display: block;
            font-size: 14px;
        }

        button {
            width: 100%;
            background-color: #2a5298;
            border: none;
            padding: 10px;
            border-radius: 8px;
            color: #fff;
            font-size: 15px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #1e3c72;
        }

        /* Mobile specific adjustments */
        @media (max-width: 576px) {
            body::before { backdrop-filter: none; background-color: rgba(0,0,0,0.35); }
            .login-box { padding: 18px; width: 92%; max-width: 360px; }
            .school-logo { width: 60px; height: 60px; margin-bottom: 12px; }
            h3 { font-size: 16px; }
            label { font-size: 13px; }
            input.form-control { font-size: 14px; padding: 8px; }
            button { padding: 9px; font-size: 14px; }
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h3>Sistem Manajemen Dokumen Sekolah</h3>
       <img src="{{ asset('images/logo-smator.png') }}" alt="Logo Sekolah" class="school-logo">
    <p class="text-muted" style="font-size: 12px; margin-top: 8px;">Portal Login Sekolah</p>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>

            <div class="mb-3 text-start">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit">Masuk</button>
        </form>
    </div>

</body>
</html>
