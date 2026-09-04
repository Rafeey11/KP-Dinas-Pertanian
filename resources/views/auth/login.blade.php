<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CWS Penyuluh</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; display: flex; }
        .split-left { flex: 1; background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary)); color: white; display: flex; flex-direction: column; justify-content: center; padding: 4rem; position: relative; overflow: hidden; }
        .split-left::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('https://images.unsplash.com/photo-1592982537447-6f233425e24c?q=80&w=2000&auto=format&fit=crop') center/cover; opacity: 0.15; z-index: 1; mix-blend-mode: overlay; }
        .split-left-content { position: relative; z-index: 2; max-width: 500px; animation: fadeIn 1s ease-out; }
        .split-right { width: 500px; background: var(--color-surface); display: flex; align-items: center; justify-content: center; padding: 3rem; box-shadow: -10px 0 30px rgba(0,0,0,0.05); z-index: 10; }
        .login-box { width: 100%; max-width: 380px; animation: slideUp 0.6s ease-out; }
        @media (max-width: 900px) { .split-left { display: none; } .split-right { width: 100%; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>

    <div class="split-left">
        <div class="split-left-content">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 80px; margin-bottom: 2rem; background: white; padding: 10px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.2;">Sistem Informasi Penyuluh Pertanian</h1>
            <p style="font-size: 1.2rem; opacity: 0.9; line-height: 1.6; max-width: 400px;">Dinas Pertanian Kabupaten Garut. Mendukung ketahanan pangan melalui digitalisasi dan evaluasi kinerja penyuluh.</p>
        </div>
    </div>

    <div class="split-right">
        <div class="login-box">
            <h2 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--color-text);">Selamat Datang</h2>
            <p style="color: var(--color-text-muted); margin-bottom: 2.5rem; font-size: 0.9rem;">Masuk ke akun Admin CWS atau BPP Anda</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                @if($errors->any())
                    <div style="background: #FEE2E2; color: #991B1B; padding: var(--sp-3); border-radius: var(--rounded); font-size: 0.85rem; margin-bottom: var(--sp-4); border-left: 4px solid #DC2626;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div style="margin-bottom: 1.25rem;">
                    <label for="username" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text-secondary);">Username / Email</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required placeholder="Ketik username Anda..." style="width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--color-border); border-radius: var(--rounded-lg); font-size: 0.95rem; transition: all 0.3s;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.outline='none';" onblur="this.style.borderColor='var(--color-border)';">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text-secondary);">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" style="width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--color-border); border-radius: var(--rounded-lg); font-size: 0.95rem; transition: all 0.3s;" onfocus="this.style.borderColor='var(--color-primary)'; this.style.outline='none';" onblur="this.style.borderColor='var(--color-border)';">
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
                    <label style="font-size: 0.85rem; color: var(--color-text-secondary); display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="remember" style="accent-color: var(--color-primary); width: 16px; height: 16px;"> Ingat Saya
                    </label>
                </div>

                <button type="submit" style="width: 100%; background: var(--color-primary); color: white; border: none; padding: 0.875rem; border-radius: var(--rounded-lg); font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='var(--color-primary-dark)';" onmouseout="this.style.background='var(--color-primary)';">
                    Mulai Sesi <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </button>
            </form>
            <div style="margin-top: 2rem; text-align: center;">
                <a href="{{ route('home') }}" style="color: var(--color-text-muted); text-decoration: none; font-size: 0.85rem; transition: color 0.3s;" onmouseover="this.style.color='var(--color-primary)';" onmouseout="this.style.color='var(--color-text-muted)';">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>
