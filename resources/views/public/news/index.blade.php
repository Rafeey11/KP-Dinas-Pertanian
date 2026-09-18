@extends('layouts.public')

@section('title', 'Berita & Kegiatan')

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <span>Berita & Kegiatan</span>
        </div>
        <h1>Berita & Kegiatan</h1>
        <p class="subtitle">Informasi terbaru seputar kegiatan penyuluhan di Kabupaten Garut</p>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-8); padding-bottom: var(--sp-12);">
    {{-- Search --}}
    <form action="{{ route('news.index') }}" method="GET" class="filter-bar">
        <input type="text" name="search" class="form-input" placeholder="Cari judul berita..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-search"></i> Cari</button>
        @if(request('search'))
            <a href="{{ route('news.index') }}" class="btn btn-outline btn-sm">Reset</a>
        @endif
    </form>

    {{-- News Grid --}}
    <div class="grid-3">
        @foreach($news as $item)
        <div class="card news-card">
            <img src="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : 'https://placehold.co/600x400/1B5E20/ffffff?text=CWS+Berita' }}" alt="{{ $item->title }}" class="card-img">
            <div class="card-body">
                <div class="news-meta">
                    <span class="news-category">{{ $item->category->name ?? 'Kegiatan' }}</span>
                    <span class="news-date">{{ $item->published_at->format('d M Y') }}</span>
                </div>
                <h3 class="news-title">{{ $item->title }}</h3>
                <p class="news-excerpt">{{ $item->excerpt ?? Str::limit(strip_tags($item->content), 100) }}</p>
                <div class="news-footer">
                    <div class="news-author">
                        <div class="news-author-avatar"><i class="fa-solid fa-user"></i></div>
                        {{ Str::limit($item->penyuluh->name ?? ($item->bpp->name ?? 'Admin CWS'), 20) }}
                    </div>
                    <a href="{{ route('news.show', $item->slug) }}" class="news-readmore">Selengkapnya →</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($news->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-newspaper"></i>
        <p>Belum ada berita yang diterbitkan.</p>
    </div>
    @endif

    <div class="pagination-wrap">
        {{ $news->links('pagination::simple-default') }}
    </div>
</div>

@endsection
