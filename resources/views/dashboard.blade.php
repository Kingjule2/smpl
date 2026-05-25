<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - SMPL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f0d1a;
            color: #f1f0f7;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 32px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .nav-logo img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .nav-title {
            font-size: 18px;
            font-weight: 700;
            background: linear-gradient(135deg, #f1f0f7, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-user-name {
            font-size: 14px;
            color: #9896a8;
        }

        .nav-user-name strong {
            color: #f1f0f7;
        }

        .btn-logout {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #f1f0f7;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 32px;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
        }

        .welcome-emoji {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-desc {
            font-size: 15px;
            color: #9896a8;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <div class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="SMPL">
            </div>
            <span class="nav-title">SMPL</span>
        </div>

        <div class="nav-user">
            <span class="nav-user-name">Halo, <strong>{{ Auth::user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Keluar</button>
            </form>
        </div>
    </nav>

    <div class="dashboard-content">
        <div class="welcome-card">
            <div class="welcome-emoji">🎉</div>
            <h1 class="welcome-title">Selamat Datang di Dashboard!</h1>
            <p class="welcome-desc">Anda berhasil masuk ke aplikasi SMPL. Halaman ini akan dikembangkan lebih lanjut.</p>
        </div>
    </div>
</body>
</html>
