@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Administrator CWS')
@section('content')
{{-- WELCOME MESSAGE --}}
<div style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); border-radius: var(--rounded-lg); padding: var(--sp-6) var(--sp-8); color: #fff; margin-bottom: var(--sp-6); display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-md);">
    <div>
        <h2 style="margin: 0 0 var(--sp-2) 0; font-size: 1.5rem;">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p style="margin: 0; opacity: 0.9;">Pusat kendali dan monitoring data BPP (Balai Penyuluhan Pertanian) Kabupaten Garut.</p>
    </div>
    <div style="font-size: 3rem; opacity: 0.2;">
        <i class="fa-solid fa-building-columns"></i>
    </div>
</div>

@if($pendingNews > 0 || $pendingVideos > 0)
<div style="background: #FEF3C7; color: #92400E; padding: var(--sp-4) var(--sp-6); border-radius: var(--rounded-lg); margin-bottom: var(--sp-6); display: flex; flex-direction: column; gap: var(--sp-2); border: 1px solid #FDE68A;">
    <h3 style="margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-bell"></i> Pemberitahuan Pending
    </h3>
    <ul style="margin: 0; padding-left: 20px;">
        @if($pendingNews > 0)
            <li>Terdapat <strong>{{ $pendingNews }}</strong> pengajuan Berita baru yang <a href="{{ route('admin.news.index', ['status' => 'pending_approval']) }}" style="color: #92400E; text-decoration: underline;">menunggu persetujuan</a>.</li>
        @endif
        @if($pendingVideos > 0)
            <li>Terdapat <strong>{{ $pendingVideos }}</strong> pengajuan Video baru yang <a href="{{ route('admin.videos.index') }}" style="color: #92400E; text-decoration: underline;">menunggu persetujuan</a>.</li>
        @endif
    </ul>
</div>
@endif

{{-- STATS GRID --}}
<div class="grid-4" style="margin-bottom: var(--sp-6);">
    <div class="stat-card">
        <div class="stat-icon" style="color: #16a34a; background: #dcfce7;">
            <i class="fa-solid fa-building"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Total BPP</div>
            <div class="stat-value">{{ $bppCount }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="color: #2563eb; background: #dbeafe;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Total Penyuluh</div>
            <div class="stat-value">{{ $penyuluhCount }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="color: #ea580c; background: #ffedd5;">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Berita Publikasi</div>
            <div class="stat-value">{{ $newsCount }}</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="color: #9333ea; background: #f3e8ff;">
            <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Laporan Masuk (Pending)</div>
            <div class="stat-value">{{ $pendingReports }}</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
        <div class="card-header">
            <h4 class="card-header-title">Aktivitas Laporan Terbaru</h4>
        </div>
        <div style="padding: var(--sp-5); text-align: center; color: var(--color-text-muted);">
            <a href="{{ route('admin.report-validation.index') }}" class="btn btn-primary">Lihat & Validasi Laporan</a>
        </div>
    </div>
    
    <div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
        <div class="card-header">
            <h4 class="card-header-title">Data BPP Cepat</h4>
        </div>
        <div style="padding: var(--sp-5); text-align: center; color: var(--color-text-muted);">
            <a href="{{ route('admin.bpp.index') }}" class="btn btn-outline">Lihat Seluruh Data BPP</a>
        </div>
    </div>
</div>
@endsection
