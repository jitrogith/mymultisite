<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#252f60">
    <title>Masuk Admin | Portal Pemerintah</title>
    <style>
        :root { color-scheme: light; --navy: #252f60; --navy-dark: #1b244e; --gold: #eeae43; --ink: #222b45; --muted: #778097; --line: #e4e7ef; font-family: "Segoe UI", Arial, sans-serif; }
        * { box-sizing: border-box; }
        body { display: grid; min-height: 100vh; margin: 0; padding: 24px; place-items: center; background: #f5f6f9; color: var(--ink); }
        .login-card { width: min(100%, 410px); overflow: hidden; border: 1px solid var(--line); border-radius: 9px; background: #fff; box-shadow: 0 18px 50px #1b244e12; }
        .login-header { padding: 27px 30px 24px; background: var(--navy); color: white; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .crest { display: grid; width: 43px; height: 48px; place-items: center; border: 1px solid #eeae4380; border-radius: 7px; color: var(--gold); }
        .crest svg { width: 31px; height: 35px; }
        .brand-kicker { margin-bottom: 4px; color: #efc879; font-size: 8px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .brand strong { display: block; font-family: Georgia, "Times New Roman", serif; font-size: 14px; }
        .login-header p { margin: 20px 0 0; color: #d3d8e8; font-size: 10px; line-height: 1.6; }
        .login-body { padding: 27px 30px 30px; }
        h1 { margin: 0; color: var(--navy); font-family: Georgia, "Times New Roman", serif; font-size: 22px; }
        .intro { margin: 7px 0 22px; color: var(--muted); font-size: 10px; }
        .field { margin-bottom: 16px; }
        label { display: block; margin-bottom: 7px; color: #3c4560; font-size: 10px; font-weight: 700; }
        input { width: 100%; height: 41px; padding: 0 11px; border: 1px solid var(--line); border-radius: 5px; outline: 0; color: var(--ink); font-size: 11px; }
        input:focus { border-color: #8d96b4; box-shadow: 0 0 0 3px #252f6010; }
        .error { margin: 0 0 15px; padding: 10px 11px; border: 1px solid #f1d0cc; border-radius: 4px; background: #fff5f4; color: #a7443a; font-size: 10px; line-height: 1.5; }
        .submit { width: 100%; height: 41px; margin-top: 2px; border: 0; border-radius: 5px; background: var(--gold); color: #2c2c36; font-size: 10px; font-weight: 800; }
        .submit:hover { background: #f2bb5d; }
        .login-footer { margin-top: 19px; text-align: center; }
        .login-footer a { color: var(--navy); font-size: 10px; font-weight: 600; text-decoration: none; }
        .login-footer a:hover { text-decoration: underline; }
        @media (max-width: 450px) { .login-header, .login-body { padding-right: 22px; padding-left: 22px; } }
    </style>
</head>
<body>
    <main class="login-card">
        <header class="login-header">
            <div class="brand">
                <span class="crest" aria-hidden="true"><svg viewBox="0 0 60 68" fill="none"><path d="M30 3 54 12v18c0 16-10.2 27.9-24 34C16.2 57.9 6 46 6 30V12L30 3Z" stroke="currentColor" stroke-width="2"/><path d="M18 42h24M22 37V25l8-5 8 5v12m-12 0v-8h8v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <span><span class="brand-kicker">Situs resmi pemerintah</span><strong>Portal Pemerintah Daerah</strong></span>
            </div>
            <p>Area pengelolaan informasi situs. Masuk menggunakan akun administrator yang telah disiapkan.</p>
        </header>
        <div class="login-body">
            <h1>Masuk administrator</h1>
            <p class="intro">Gunakan nama pengguna dan kata sandi admin.</p>
            @if ($errors->any())
                <div class="error" role="alert">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="field">
                    <label for="username">Nama pengguna</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Kata sandi</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </div>
                <button class="submit" type="submit">Masuk ke panel admin</button>
            </form>
            <div class="login-footer"><a href="{{ route('home') }}">← Kembali ke situs publik</a></div>
        </div>
    </main>
</body>
</html>
