@extends('layouts.public')

@section('title', $bpp->name)

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <a href="{{ route('bpp.index') }}">Keragaan BPP</a>
            <span class="breadcrumb-sep">›</span>
            <span>{{ $bpp->name }}</span>
        </div>
        <div style="display: flex; align-items: center; gap: var(--sp-4); margin-top: var(--sp-4);">
            <div class="bpp-icon" style="width: 64px; height: 64px; min-width: 64px; font-size: 1.5rem; background: rgba(255,255,255,0.15); color: #fff;">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <h1 style="margin: 0;">{{ $bpp->name }}</h1>
                <p class="subtitle" style="margin-top: 4px;">{{ $bpp->district }} · {{ $bpp->penyuluhs->count() }} Personil</p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-8); padding-bottom: var(--sp-12);">
    <div class="grid-sidebar">
        {{-- LEFT: Personil --}}
        <div>
            <h3 class="section-title mb-6">Personil BPP ({{ $bpp->penyuluhs->count() }})</h3>
            <div style="display: flex; flex-direction: column; gap: var(--sp-3);">
                @foreach($bpp->penyuluhs as $penyuluh)
                <div class="personil-item">
                    <div class="personil-avatar">
                        @if($penyuluh->photo)
                            <img src="{{ asset('storage/'.$penyuluh->photo) }}" alt="{{ $penyuluh->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <div class="personil-info">
                        <div class="personil-name">{{ $penyuluh->name }}</div>
                        <div class="personil-role">{{ $penyuluh->position }} · {{ $penyuluh->employment_status }}</div>
                    </div>
                    <a href="{{ route('penyuluh.show', $penyuluh->slug) }}" class="btn btn-outline btn-sm">Lihat Profil</a>
                </div>
                @endforeach

                @if($bpp->penyuluhs->isEmpty())
                <div class="empty-state">
                    <i class="fa-solid fa-users"></i>
                    <p>Belum ada data penyuluh.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Info Sidebar --}}
        <div>
            <div class="card mb-6">
                <div class="card-body">
                    <h4 class="font-bold mb-4" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Informasi BPP</h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-barcode"></i></div>
                            <div>
                                <div class="info-label">Kode BPP</div>
                                <div class="info-value">{{ $bpp->code ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $bpp->address ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-user-tie"></i></div>
                            <div>
                                <div class="info-label">Kontak Person</div>
                                <div class="info-value">{{ $bpp->contact_person ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <div class="info-label">Telepon</div>
                                <div class="info-value">{{ $bpp->phone ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $bpp->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-map"></i></div>
                            <div>
                                <div class="info-label">Wilayah Kerja</div>
                                <div class="info-value">{{ $bpp->working_area ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($bpp->latitude && $bpp->longitude || $bpp->google_maps_url)
            <div class="card">
                <div class="card-body">
                    <h4 class="font-bold mb-4" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Lokasi</h4>
                    @if($bpp->latitude && $bpp->longitude)
                    <div style="margin-bottom: var(--sp-4); border-radius: var(--rounded-md); overflow: hidden; height: 250px;">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            src="https://maps.google.com/maps?q={{ $bpp->latitude }},{{ $bpp->longitude }}&z=15&output=embed">
                        </iframe>
                    </div>
                    @endif
                    
                    @if($bpp->google_maps_url)
                    <a href="{{ $bpp->google_maps_url }}" target="_blank" class="btn btn-outline btn-block btn-sm" style="margin-bottom: 8px;">
                        <i class="fa-solid fa-map-location-dot"></i> Buka di Google Maps
                    </a>
                    @elseif($bpp->latitude && $bpp->longitude)
                    <a href="https://maps.google.com/?q={{ $bpp->latitude }},{{ $bpp->longitude }}" target="_blank" class="btn btn-outline btn-block btn-sm" style="margin-bottom: 8px;">
                        <i class="fa-solid fa-directions"></i> Petunjuk Arah
                    </a>
                    @endif
                    
                    @if($bpp->latitude && $bpp->longitude)
                    <div style="font-size: 0.8rem; color: var(--color-text-muted); text-align: center; margin-top: 8px;">
                        {{ $bpp->latitude }}, {{ $bpp->longitude }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
