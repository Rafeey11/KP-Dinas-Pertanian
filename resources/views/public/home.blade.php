@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

{{-- ========== HERO ========== --}}
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-seedling"></i> Kabupaten Garut
            </div>
            <h2>Selamat Datang di<br>Co Working Space Penyuluh</h2>
            <p>Platform informasi, kolaborasi, dan pelaporan penyuluh pertanian di Kabupaten Garut. Menghubungkan seluruh Balai Penyuluhan Pertanian (BPP) dan masyarakat.</p>
            <div class="hero-actions">
                <a href="{{ route('bpp.index') }}" class="btn btn-white">
                    <i class="fa-solid fa-building-columns"></i> Lihat Keragaan BPP
                </a>
                <a href="{{ route('news.index') }}" class="btn btn-outline-white">
                    <i class="fa-solid fa-newspaper"></i> Jelajahi Berita
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ========== STATS ========== --}}
<div class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value">{{ $stats['bpp_count'] }}</div>
                <div class="stat-label">Total BPP</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['penyuluh_count'] }}</div>
                <div class="stat-label">Penyuluh Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['news_count'] }}</div>
                <div class="stat-label">Berita & Kegiatan</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $stats['district_count'] }}</div>
                <div class="stat-label">Kecamatan</div>
            </div>
        </div>
    </div>
</div>

{{-- ========== BERITA TERBARU ========== --}}
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Berita & Kegiatan Terbaru</h2>
                <p class="section-subtitle">Informasi terkini seputar penyuluhan pertanian di Garut</p>
            </div>
            <a href="{{ route('news.index') }}" class="btn btn-outline btn-sm">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid-3">
            @foreach($latestNews as $news)
            <div class="card news-card">
                <img src="{{ $news->thumbnail ? asset('storage/'.$news->thumbnail) : 'https://placehold.co/600x400/1B5E20/ffffff?text=CWS+Penyuluh' }}" alt="{{ $news->title }}" class="card-img">
                <div class="card-body">
                    <div class="news-meta">
                        <span class="news-category">{{ $news->category->name ?? 'Kegiatan' }}</span>
                        <span class="news-date">{{ $news->published_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="news-title">{{ $news->title }}</h3>
                    <p class="news-excerpt">{{ $news->excerpt ?? Str::limit(strip_tags($news->content), 100) }}</p>
                    <div class="news-footer">
                        <div class="news-author">
                            <div class="news-author-avatar"><i class="fa-solid fa-user"></i></div>
                            {{ Str::limit($news->penyuluh->name ?? 'Admin', 20) }}
                        </div>
                        <a href="{{ route('news.show', $news->slug) }}" class="news-readmore">Selengkapnya →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== BPP PREVIEW ========== --}}
<section class="section" style="background: var(--color-surface);">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Keragaan BPP</h2>
                <p class="section-subtitle">Daftar Balai Penyuluhan Pertanian di Kabupaten Garut</p>
            </div>
            <a href="{{ route('bpp.index') }}" class="btn btn-outline btn-sm">Lihat Semua BPP <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="grid-3">
            @foreach($previewBpps as $bpp)
            <div class="card bpp-card">
                <div class="card-body">
                    <div class="bpp-card-header">
                        <div class="bpp-icon"><i class="fa-solid fa-building-columns"></i></div>
                        <div>
                            <div class="bpp-name">{{ $bpp->name }}</div>
                            <div class="bpp-district">{{ $bpp->district }}</div>
                        </div>
                    </div>
                    <div class="bpp-info-list">
                        <div class="bpp-info-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ Str::limit($bpp->address, 55) }}</span>
                        </div>
                        <div class="bpp-info-item">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>{{ $bpp->contact_person ?? '-' }}</span>
                        </div>
                        <div class="bpp-info-item">
                            <i class="fa-solid fa-users"></i>
                            <span>{{ $bpp->penyuluhs()->count() }} Penyuluh</span>
                        </div>
                    </div>
                    <a href="{{ route('bpp.show', $bpp->slug) }}" class="btn btn-primary btn-block btn-sm">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
