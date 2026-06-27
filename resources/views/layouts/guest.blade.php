<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CinePanel') }} — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg: #0d0d14;
            --bg-2: #13131f;
            --bg-3: #1a1a2e;
            --bg-4: #21213a;
            --border: rgba(255,255,255,.07);
            --border-2: rgba(255,255,255,.12);
            --text-1: #f0efff;
            --text-2: #9b99c0;
            --text-3: #5e5c7a;
            --accent: #f97316;
            --accent-hover: #ea580c;
            --accent-soft: rgba(249,115,22,.15);
            --accent-glow: rgba(249,115,22,.35);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --shadow: 0 4px 24px rgba(0,0,0,.55);
            --shadow-sm: 0 2px 10px rgba(0,0,0,.35);
            --font: 'Poppins', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text-1);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--bg-4); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        .auth-wrap {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 520px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .auth-brand {
            flex: 1;
            background: linear-gradient(135deg, #13131f 0%, #1a1a2e 100%);
            padding: 48px 36px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border-right: 1px solid var(--border);
        }
        .auth-brand::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: var(--accent-glow);
            opacity: .15;
        }
        .auth-brand::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -60px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(139,92,246,.08);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            position: relative;
            z-index: 1;
        }
        .logo-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--accent), #fb923c);
            border-radius: 12px;
            display: grid; place-items: center;
            font-size: 20px; color: #fff;
            box-shadow: 0 0 24px var(--accent-glow);
        }
        .logo-text {
            font-size: 22px; font-weight: 800;
        }
        .logo-text span { color: var(--accent); }
        .brand-tagline {
            font-size: 14px;
            color: var(--text-2);
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }
        .brand-tagline strong { color: var(--text-1); }
        .brand-features {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
            z-index: 1;
        }
        .brand-feat {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: var(--text-2);
        }
        .brand-feat i {
            width: 28px; height: 28px;
            border-radius: 6px;
            background: var(--accent-soft);
            color: var(--accent);
            display: grid; place-items: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .auth-form {
            width: 400px;
            padding: 48px 40px;
            background: var(--bg-2);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-form-header {
            margin-bottom: 28px;
        }
        .auth-form-header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .auth-form-header p {
            font-size: 13px;
            color: var(--text-2);
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            background: var(--bg-3);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            color: var(--text-1);
            font-size: 13px;
            font-family: var(--font);
            outline: none;
            transition: border-color .22s;
        }
        .form-input:focus { border-color: var(--accent); }
        .form-input::placeholder { color: var(--text-3); }
        .form-input-wrapper {
            position: relative;
        }
        .form-input-wrapper .form-input {
            padding-left: 40px;
        }
        .form-input-wrapper > i:first-child {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-3);
            font-size: 14px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .form-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .form-check label {
            font-size: 13px;
            color: var(--text-2);
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-family: var(--font);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .22s, transform .2s;
            box-shadow: 0 4px 14px var(--accent-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover { background: var(--accent-hover); transform: translateY(-1px); }

        .input-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
        }

        .session-status {
            padding: 10px 14px;
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.25);
            border-radius: var(--radius-sm);
            color: #10b981;
            font-size: 13px;
            margin-bottom: 18px;
        }

        @media (max-width: 768px) {
            .auth-wrap { flex-direction: column; max-width: 420px; }
            .auth-brand { padding: 32px 24px; }
            .auth-form { width: 100%; padding: 32px 24px; }
            .brand-features { display: none; }
        }
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-brand">
            <div class="brand-logo">
                <div class="logo-icon"><i class="fa-solid fa-film"></i></div>
                <span class="logo-text">Cine<span>Panel</span></span>
            </div>
            <div class="brand-tagline">
                <strong>Manajemen Produksi Film</strong><br>
                Kelola seluruh proses produksi film — dari pra-produksi hingga pasca-produksi — dalam satu platform terpadu.
            </div>
            <div class="brand-features">
                <div class="brand-feat"><i class="fa-solid fa-clapperboard"></i> Manajemen Film & Pemeran</div>
                <div class="brand-feat"><i class="fa-solid fa-file-invoice-dollar"></i> RAB & Anggaran</div>
                <div class="brand-feat"><i class="fa-solid fa-calendar-days"></i> Jadwal Produksi</div>
                <div class="brand-feat"><i class="fa-solid fa-list-check"></i> Shot List & Skenario</div>
            </div>
        </div>
        <div class="auth-form">
            {{ $slot }}
        </div>
    </div>

    <script>
    document.addEventListener('click', function(e) {
        if (e.target.id === 'togglePassword') {
            const input = document.getElementById('password');
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            e.target.className = isPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        }
    });
    </script>
</body>
</html>
