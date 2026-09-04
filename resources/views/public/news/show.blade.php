@extends('layouts.public')

@section('title', $newsItem->title)

@section('content')

<div class="page-header" style="padding-bottom: var(--sp-6);">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <a href="{{ route('news.index') }}">Berita</a>
            <span class="breadcrumb-sep">›</span>
            <span>Detail Berita</span>
        </div>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-6); padding-bottom: var(--sp-12);">
    <div class="grid-sidebar">
        {{-- LEFT: Article --}}
        <div>
            <div class="card">
                <img src="{{ $newsItem->thumbnail ? asset('storage/'.$newsItem->thumbnail) : 'https://placehold.co/1200x600/1B5E20/ffffff?text=CWS+Penyuluh' }}" alt="{{ $newsItem->title }}" style="width: 100%; max-height: 450px; object-fit: cover;">
                <div class="card-body" style="padding: var(--sp-8);">
                    <div class="news-meta mb-4">
                        <span class="news-category">{{ $newsItem->category->name ?? 'Kegiatan' }}</span>
                        <span class="news-date"><i class="fa-regular fa-calendar"></i> {{ $newsItem->published_at->format('d M Y, H:i') }}</span>
                    </div>

                    <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: var(--sp-6); line-height: 1.3;">{{ $newsItem->title }}</h1>

                    <div style="display: flex; align-items: center; gap: var(--sp-3); padding-bottom: var(--sp-6); margin-bottom: var(--sp-6); border-bottom: 1px solid var(--color-border);">
                        <div class="news-author-avatar" style="width: 42px; height: 42px; font-size: 1rem;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <div class="font-semibold">{{ $newsItem->penyuluh->name ?? $newsItem->creator->name ?? 'Admin' }}</div>
                            <div class="text-sm text-muted">{{ $newsItem->bpp->name ?? 'CWS Pusat' }}</div>
                        </div>
                    </div>

                    <div class="article-content">
                        {!! $newsItem->content !!}
                    </div>

                    @if($newsItem->activity_location || $newsItem->activity_date)
                    <div class="article-info-box mt-8">
                        <h4 class="font-bold mb-2">Informasi Kegiatan</h4>
                        @if($newsItem->activity_date)
                            <div class="text-sm mb-2"><i class="fa-regular fa-calendar-check" style="width: 16px;"></i> Pelaksanaan: {{ $newsItem->activity_date->format('d M Y') }}</div>
                        @endif
                        @if($newsItem->activity_location)
                            <div class="text-sm"><i class="fa-solid fa-location-dot" style="width: 16px;"></i> Lokasi: {{ $newsItem->activity_location }}</div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT: Sidebar --}}
        <div>
            @if($newsItem->bpp)
            <div class="card mb-6">
                <div class="card-body" style="padding: var(--sp-5);">
                    <h4 class="font-bold mb-4" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">BPP Terkait</h4>
                    <div class="bpp-card-header">
                        <div class="bpp-icon"><i class="fa-solid fa-building-columns"></i></div>
                        <div>
                            <div class="bpp-name">{{ $newsItem->bpp->name }}</div>
                            <div class="bpp-district">{{ $newsItem->bpp->district }}</div>
                        </div>
                    </div>
                    <a href="{{ route('bpp.show', $newsItem->bpp->slug) }}" class="btn btn-outline btn-block btn-sm mt-4">Lihat Profil BPP</a>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-body" style="padding: var(--sp-5);">
                    <h4 class="font-bold mb-4" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Bagikan</h4>
                    <div style="display: flex; gap: var(--sp-2);">
                        <a href="https://wa.me/?text={{ urlencode($newsItem->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-outline btn-sm" style="flex:1;"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-outline btn-sm" style="flex:1;"><i class="fa-brands fa-facebook"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($newsItem->title) }}" target="_blank" class="btn btn-outline btn-sm" style="flex:1;"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
