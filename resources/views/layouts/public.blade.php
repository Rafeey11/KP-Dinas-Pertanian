<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CWS GARUT - Platform informasi dan pelaporan penyuluh pertanian Kabupaten Garut">
    <title>@yield('title', 'CWS GARUT') | CWS GARUT</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('styles')
</head>
<body>

    {{-- ========== NAVBAR ========== --}}
    <nav class="navbar">
        <div class="container navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo CWS" class="navbar-brand-logo">
                <div class="navbar-brand-text">
                    <h1>CWS GARUT</h1>
                    <span>Kementerian Pertanian | BPPSDMP</span>
                </div>
            </a>

            <div class="navbar-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('bpp.index') }}" class="{{ request()->routeIs('bpp.*') ? 'active' : '' }}">Keragaan BPP</a>
                <a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">Berita</a>
                <a href="{{ route('video.index') }}" class="{{ request()->routeIs('video.*') ? 'active' : '' }}">Dashboard Video</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
            </div>

            @auth
                @if(in_array(auth()->user()->role, ['admin', 'admin_tim_kerja']))
                    <a href="{{ route('admin.dashboard') }}" class="btn-login">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('bpp.dashboard') }}" class="btn-login">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
            @endauth
        </div>
    </nav>

    {{-- ========== MAIN CONTENT ========== --}}
    <main>
        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3><img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 28px; display: inline-block; vertical-align: middle; margin-right: 8px;"> CWS GARUT</h3>
                    <p>Platform informasi, kolaborasi, dan pelaporan penyuluh pertanian di Kabupaten Garut. Menghubungkan seluruh Balai Penyuluhan Pertanian.</p>
                </div>
                <div>
                    <div class="footer-title">Menu</div>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Beranda</a>
                        <a href="{{ route('bpp.index') }}">Keragaan BPP</a>
                        <a href="{{ route('news.index') }}">Berita</a>
                        <a href="{{ route('video.index') }}">Dashboard Video</a>
                    </div>
                </div>
                <div>
                    <div class="footer-title">Informasi</div>
                    <div class="footer-links">
                        <a href="{{ route('about') }}">Tentang Kami</a>
                        @auth
                            @if(in_array(auth()->user()->role, ['admin', 'admin_tim_kerja']))
                                <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                            @else
                                <a href="{{ route('bpp.dashboard') }}">Dashboard BPP</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}">Login BPP</a>
                        @endauth
                    </div>
                </div>
                <div>
                    <div class="footer-title">Kontak</div>
                    <div class="footer-links">
                        <a href="#"><i class="fa-solid fa-location-dot"></i> Jl. Pembangunan No. 183, Garut</a>
                        <a href="tel:0262233155"><i class="fa-solid fa-phone"></i> (0262) 233155</a>
                        <a href="mailto:info@cws-garut.go.id"><i class="fa-solid fa-envelope"></i> info@cws-garut.go.id</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} CWS GARUT. All rights reserved.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
