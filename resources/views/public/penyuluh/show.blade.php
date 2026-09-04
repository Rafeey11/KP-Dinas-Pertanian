@extends('layouts.public')

@section('title', 'Profil ' . $penyuluh->name)

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <a href="{{ route('bpp.index') }}">Keragaan BPP</a>
            <span class="breadcrumb-sep">›</span>
            <a href="{{ route('bpp.show', $penyuluh->bpp->slug) }}">{{ $penyuluh->bpp->name }}</a>
            <span class="breadcrumb-sep">›</span>
            <span>{{ $penyuluh->name }}</span>
        </div>
        <h1>Profil Penyuluh</h1>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-8); padding-bottom: var(--sp-12);">
    <div class="grid-sidebar-reverse">
        {{-- LEFT: Photo Card --}}
        <div>
            <div class="card mb-6">
                <div class="card-body text-center" style="padding: var(--sp-8);">
                    <div class="profile-avatar">
                        @if($penyuluh->photo)
                            <img src="{{ asset('storage/'.$penyuluh->photo) }}" alt="{{ $penyuluh->name }}">
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <h2 style="font-size: 1.25rem; font-weight: 700;">{{ $penyuluh->name }}</h2>
                    <div class="badge badge-success mt-2">{{ $penyuluh->employment_status }}</div>
                    <div class="text-sm text-muted mt-2">{{ $penyuluh->position }}</div>
                    
                    <div style="margin-top: var(--sp-6); padding-top: var(--sp-4); border-top: 1px solid var(--color-border); text-align: left;">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                                <div>
                                    <div class="info-label">Telepon</div>
                                    <div class="info-value">{{ $penyuluh->phone ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                                <div>
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $penyuluh->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-icon"><i class="fa-solid fa-building-columns"></i></div>
                                <div>
                                    <div class="info-label">Unit Kerja</div>
                                    <div class="info-value"><a href="{{ route('bpp.show', $penyuluh->bpp->slug) }}" style="color: var(--color-primary);">{{ $penyuluh->bpp->name }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Detail --}}
        <div>
            <div class="card mb-6">
                <div class="card-body" style="padding: var(--sp-8);">
                    <h3 class="section-title mb-6" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Informasi Kepegawaian</h3>
                    <div class="grid-2" style="row-gap: var(--sp-5);">
                        <div>
                            <div class="info-label">NIP</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->nip ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Jabatan</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->position ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Status Kepegawaian</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->employment_status ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Alamat</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->address ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="padding: var(--sp-8);">
                    <h3 class="section-title mb-6" style="padding-bottom: var(--sp-3); border-bottom: 1px solid var(--color-border);">Wilayah Binaan</h3>
                    <div class="grid-2" style="row-gap: var(--sp-5);">
                        <div>
                            <div class="info-label">Wilayah Kerja</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->working_area ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Desa Binaan</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->village_area ?? '-' }}</div>
                        </div>
                        <div style="grid-column: span 2;">
                            <div class="info-label">Kelompok Tani Binaan</div>
                            <div class="info-value" style="font-size: 1rem;">{{ $penyuluh->farmer_group ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
