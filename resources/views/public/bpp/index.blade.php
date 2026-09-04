@extends('layouts.public')

@section('title', 'Keragaan BPP')

@section('content')

<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep">›</span>
            <span>Keragaan BPP</span>
        </div>
        <h1>Keragaan BPP</h1>
        <p class="subtitle">Daftar 42 Balai Penyuluhan Pertanian (BPP) di Kabupaten Garut</p>
    </div>
</div>

<div class="container" style="padding-top: var(--sp-8); padding-bottom: var(--sp-12);">
    {{-- Filter Bar --}}
    <form action="{{ route('bpp.index') }}" method="GET" class="filter-bar">
        <input type="text" name="search" class="form-input" placeholder="Cari nama BPP atau kecamatan..." value="{{ request('search') }}">
        <select name="district" class="form-select">
            <option value="">Semua Kecamatan</option>
            @foreach($districts as $district)
                <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-search"></i> Cari</button>
        @if(request('search') || request('district'))
            <a href="{{ route('bpp.index') }}" class="btn btn-outline btn-sm">Reset</a>
        @endif
    </form>

    {{-- Result Count --}}
    <div class="result-count">
        Menampilkan {{ $bpps->firstItem() ?? 0 }}–{{ $bpps->lastItem() ?? 0 }} dari {{ $bpps->total() }} BPP
    </div>

    {{-- BPP Grid --}}
    <div class="grid-3">
        @foreach($bpps as $bpp)
        <div class="card bpp-card">
            <div class="card-body">
                <div style="margin-bottom: var(--sp-3);">
                    <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size:6px"></i> Aktif</span>
                </div>
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
                    @if($bpp->latitude && $bpp->longitude)
                    <div class="bpp-info-item">
                        <i class="fa-solid fa-map-pin"></i>
                        <span>{{ $bpp->latitude }}, {{ $bpp->longitude }}</span>
                    </div>
                    @endif
                </div>
                <a href="{{ route('bpp.show', $bpp->slug) }}" class="btn btn-primary btn-block btn-sm">Lihat Detail</a>
            </div>
        </div>
        @endforeach
    </div>

    @if($bpps->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-building"></i>
        <p>Tidak ditemukan BPP yang sesuai.</p>
    </div>
    @endif

    {{-- Pagination --}}
    <div class="pagination-wrap">
        {{ $bpps->links('pagination::simple-default') }}
    </div>
</div>

@endsection
