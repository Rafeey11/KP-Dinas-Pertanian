@extends('layouts.public')

@section('title', 'Dashboard Video')

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <span>Dashboard Video</span>
        </div>
        <h1>Dashboard Video</h1>
        <p class="subtitle">Kumpulan video edukasi dan dokumentasi kegiatan penyuluhan</p>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-8); padding-bottom: var(--sp-12);">
    <div class="grid-3">
        @foreach($videos as $video)
        <div class="card video-card">
            <div class="video-embed">
                @if($video->source_type == 'youtube')
                    @php
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $video->video_url, $match);
                        $youtube_id = $match[1] ?? null;
                    @endphp
                    @if($youtube_id)
                        <iframe src="https://www.youtube.com/embed/{{ $youtube_id }}" allowfullscreen loading="lazy"></iframe>
                    @else
                        <div class="video-placeholder"><span>Video tidak tersedia</span></div>
                    @endif
                @else
                    <div class="video-placeholder">
                        <a href="{{ $video->video_url }}" target="_blank" class="btn btn-white btn-sm">
                            <i class="fa-brands fa-google-drive"></i> Buka Video
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="news-meta mb-2">
                    <span class="badge badge-info">{{ strtoupper($video->source_type) }}</span>
                    @if($video->activity_date)
                        <span class="news-date">{{ $video->activity_date->format('d M Y') }}</span>
                    @endif
                </div>
                <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: var(--sp-2);">{{ $video->title }}</h3>
                <p class="text-sm text-muted" style="line-height: 1.5;">{{ Str::limit($video->description, 80) }}</p>
                @if($video->bpp)
                    <div class="text-sm mt-4" style="color: var(--color-primary); font-weight: 500;"><i class="fa-solid fa-building-columns"></i> {{ $video->bpp->name }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($videos->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-video"></i>
        <p>Belum ada video yang ditambahkan.</p>
    </div>
    @endif

    <div class="pagination-wrap">
        {{ $videos->links('pagination::simple-default') }}
    </div>
</div>

@endsection
