<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found | CWS GARUT</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { margin: 0; padding: 0; background: var(--color-bg); font-family: 'Inter', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; }
        .error-container { max-width: 500px; padding: 2rem; animation: fadeIn 0.5s ease-out; }
        .error-code { font-size: 8rem; font-weight: 900; color: var(--color-primary); line-height: 1; margin-bottom: 1rem; text-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }
        .error-title { font-size: 1.75rem; font-weight: 700; color: var(--color-text); margin-bottom: 1rem; }
        .error-desc { font-size: 1rem; color: var(--color-text-muted); margin-bottom: 2.5rem; line-height: 1.6; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-title">Halaman Tidak Ditemukan</div>
        <div class="error-desc">Maaf, halaman yang Anda cari mungkin telah dihapus, namanya diubah, atau sementara tidak tersedia.</div>
        <a href="{{ url('/') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem; display: inline-flex; align-items: center; gap: 10px; border-radius: 999px;">
            <i class="fa-solid fa-home"></i> Kembali ke Beranda
        </a>
    </div>

</body>
</html>
