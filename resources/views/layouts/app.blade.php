<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | CWS GARUT</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('styles')
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
        }
        
        body {
            background-color: var(--color-bg);
            margin: 0;
            overflow-x: hidden;
            display: flex;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--color-primary-dark);
            color: rgba(255,255,255,0.8);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 var(--sp-4);
            background: rgba(0,0,0,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .sidebar-brand img {
            height: 32px;
            width: auto;
        }

        .sidebar-menu {
            padding: var(--sp-4) 0;
            flex: 1;
            overflow-y: auto;
        }

        .menu-label {
            padding: var(--sp-2) var(--sp-5);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            opacity: 0.5;
            margin-top: var(--sp-4);
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            padding: var(--sp-3) var(--sp-5);
            color: rgba(255,255,255,0.7);
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: var(--color-primary-light);
            font-weight: 500;
        }

        /* MAIN CONTENT */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .top-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--sp-6);
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .header-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--color-text);
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--color-text);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--color-text-muted);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: var(--color-primary-lighter);
            color: var(--color-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .notification-dropdown {
            position: relative;
            margin-right: var(--sp-4);
        }
        .notification-btn {
            background: transparent;
            border: none;
            color: var(--color-text-muted);
            font-size: 1.2rem;
            cursor: pointer;
            position: relative;
            padding: var(--sp-2);
            transition: var(--transition);
        }
        .notification-btn:hover {
            color: var(--color-primary);
        }
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #EF4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 10px;
            border: 2px solid #fff;
            min-width: 18px;
            text-align: center;
        }

        /* CONTENT AREA */
        .content-area {
            padding: var(--sp-6);
            flex: 1;
        }

        /* UTILS */
        .card-header {
            padding: var(--sp-4) var(--sp-5);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header-title {
            font-weight: 600;
            font-size: 1.05rem;
            margin: 0;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: var(--sp-3) var(--sp-4);
            border-bottom: 1px solid var(--color-border-light);
            text-align: left;
            font-size: 0.875rem;
        }
        .table th {
            background: var(--color-surface-alt);
            font-weight: 600;
            color: var(--color-text-secondary);
        }
        .table tbody tr:hover {
            background: var(--color-bg);
        }
    </style>
</head>
<body>

    {{-- SIDEBAR OVERLAY --}}
    <div class="sidebar-overlay" onclick="document.body.classList.remove('sidebar-open')"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="CWS Logo">
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <span>CWS GARUT</span>
                    <span style="font-size: 0.7rem; font-weight: 500; opacity: 0.8; line-height: 1.2;">Kementerian Pertanian<br>BPPSDMP</span>
                </div>
            </a>
        </div>

        <div class="sidebar-menu">
            @if(in_array(auth()->user()->role, ['admin', 'admin_tim_kerja']))
                {{-- ADMIN & TIM KERJA MENU --}}
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>

                <div class="menu-label">Manajemen</div>
                
                <a href="{{ route('admin.bpp.index') }}" class="menu-item {{ request()->routeIs('admin.bpp.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building-columns"></i> {{ auth()->user()->role === 'admin_tim_kerja' ? 'Data BPP Binaan' : 'Data BPP' }}
                </a>
                <a href="{{ route('admin.penyuluh.index') }}" class="menu-item {{ request()->routeIs('admin.penyuluh.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Data Penyuluh
                </a>
                
                <div class="menu-label">Laporan & Evaluasi</div>
                
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.report-requests.index') }}" class="menu-item {{ request()->routeIs('admin.report-requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice"></i> Permintaan Laporan
                </a>
                @endif
                <a href="{{ route('admin.report-validation.index') }}" class="menu-item {{ request()->routeIs('admin.report-validation.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-check"></i> Validasi Laporan
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.report-types.index') }}" class="menu-item {{ request()->routeIs('admin.report-types.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list"></i> Jenis Laporan
                </a>
                @endif

                <div class="menu-label">Konten Web</div>
                
                <a href="{{ route('admin.news.index') }}" class="menu-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> Persetujuan Berita
                </a>
                
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.videos.index') }}" class="menu-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                    <i class="fa-brands fa-youtube"></i> Video Edukasi
                </a>
                @endif
                
                <a href="{{ route('admin.bank-sops.index') }}" class="menu-item {{ request()->routeIs('admin.bank-sops.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open"></i> Bank SOP
                </a>

                @if(auth()->user()->role === 'admin')
                <div class="menu-label">Pengaturan</div>
                
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i> Akun Pengguna
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="menu-item {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i> Audit Log
                </a>
                @endif

            @elseif(auth()->user()->role === 'bpp')
                {{-- BPP MENU --}}
                <a href="{{ route('bpp.dashboard') }}" class="menu-item {{ request()->routeIs('bpp.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>

                <div class="menu-label">Internal BPP</div>
                
                <a href="{{ route('bpp.profil.edit') }}" class="menu-item {{ request()->routeIs('bpp.profil.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building"></i> Profil BPP
                </a>
                <a href="{{ route('bpp.penyuluh.index') }}" class="menu-item {{ request()->routeIs('bpp.penyuluh.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Personil Penyuluh
                </a>

                <div class="menu-label">Pelaporan (Monev)</div>
                <a href="{{ route('bpp.laporan.index') }}" class="menu-item {{ request()->routeIs(['bpp.laporan.index','bpp.laporan.show','bpp.laporan.upload']) ? 'active' : '' }}">
                    <i class="fa-solid fa-file-arrow-up"></i> Upload Laporan
                </a>
                <a href="{{ route('bpp.laporan.history') }}" class="menu-item {{ request()->routeIs('bpp.laporan.history') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Laporan
                </a>

                <div class="menu-label">Publikasi</div>

                <a href="{{ route('bpp.berita.index') }}" class="menu-item {{ request()->routeIs('bpp.berita.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> Kelola Berita
                </a>
                <a href="{{ route('bpp.video.index') }}" class="menu-item {{ request()->routeIs('bpp.video.*') ? 'active' : '' }}">
                    <i class="fa-brands fa-youtube"></i> Video Edukasi
                </a>

                <div class="menu-label">Sumber Daya</div>
                <a href="{{ route('bpp.bank-sops.index') }}" class="menu-item {{ request()->routeIs('bpp.bank-sops.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open"></i> Bank SOP
                </a>
            @endif

            <div style="margin-top: auto; padding-top: var(--sp-6);">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="menu-item" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left; font-family: inherit; font-size: inherit;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="main-wrapper">
        
        {{-- HEADER --}}
        <header class="top-header">
            <div style="display: flex; align-items: center;">
                <button class="mobile-toggle" onclick="document.body.classList.toggle('sidebar-open')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="header-title">
                    @yield('header_title', 'Dashboard')
                </div>
            </div>

            <div class="header-user">
                <a href="{{ route('home') }}" class="btn btn-outline-white btn-sm" style="border-color: var(--color-border); color: var(--color-text); margin-right: var(--sp-4);">
                    <i class="fa-solid fa-globe"></i> Lihat Web
                </a>
                
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp
                <div class="notification-dropdown">
                    <button class="notification-btn" onclick="window.location.href='{{ route('notifications.index') }}'">
                        <i class="fa-regular fa-bell"></i>
                        @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </button>
                </div>

                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        @if(auth()->user()->role === 'admin')
                            Administrator CWS
                        @elseif(auth()->user()->role === 'admin_tim_kerja')
                            Admin Tim Kerja
                        @else
                            Operator {{ auth()->user()->bpp->name ?? 'BPP' }}
                        @endif
                    </div>
                </div>
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="content-area">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.form-delete');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DC2626',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: 'var(--color-surface)',
                        color: 'var(--color-text)'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
