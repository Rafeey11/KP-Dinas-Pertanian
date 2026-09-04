@extends('layouts.app')
@section('title', 'BPP Dashboard')
@section('header_title', 'Dashboard BPP')
@section('content')

@if(!$bpp)
<div style="background: #FEE2E2; color: #991B1B; padding: var(--sp-4); border-radius: var(--rounded-lg); margin-bottom: var(--sp-6);">
    <h4 style="margin: 0 0 8px 0;"><i class="fa-solid fa-triangle-exclamation"></i> Perhatian: Akun Tidak Terkait BPP</h4>
    <p style="margin: 0; font-size: 0.9rem;">Akun Anda saat ini belum dikaitkan dengan profil BPP manapun. Silakan hubungi Administrator CWS untuk menghubungkan akun Anda agar dapat mengelola data BPP, penyuluh, dan laporan.</p>
</div>
@else

{{-- WELCOME MESSAGE --}}
<div style="background: linear-gradient(135deg, #16a34a, #15803d); border-radius: var(--rounded-lg); padding: var(--sp-6) var(--sp-8); color: #fff; margin-bottom: var(--sp-6); display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-md);">
    <div>
        <h2 style="margin: 0 0 var(--sp-2) 0; font-size: 1.5rem;">Selamat Datang, BPP {{ $bpp->name }}</h2>
        <p style="margin: 0; opacity: 0.9;">Kelola data penyuluh, publikasi berita, dan laporan BPP Anda di sini.</p>
    </div>
    <div style="font-size: 3rem; opacity: 0.2;">
        <i class="fa-solid fa-leaf"></i>
    </div>
</div>

@if($pendingNews > 0 || $rejectedNews > 0 || $pendingVideos > 0 || $rejectedVideos > 0)
<div style="background: #F8FAFC; color: #0F172A; padding: var(--sp-4) var(--sp-6); border-radius: var(--rounded-lg); margin-bottom: var(--sp-6); display: flex; flex-direction: column; gap: var(--sp-2); border: 1px solid #E2E8F0;">
    <h3 style="margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-info" style="color: #3B82F6;"></i> Status Pengajuan Publikasi
    </h3>
    <ul style="margin: 0; padding-left: 20px;">
        @if($pendingNews > 0)
            <li><strong>{{ $pendingNews }}</strong> Berita Anda sedang <span style="color: #D97706;">menunggu persetujuan</span> Admin.</li>
        @endif
        @if($rejectedNews > 0)
            <li><strong>{{ $rejectedNews }}</strong> Berita Anda <span style="color: #DC2626;">ditolak</span> oleh Admin. <a href="{{ route('bpp.berita.index') }}" style="text-decoration: underline;">Cek sekarang</a>.</li>
        @endif
        @if($pendingVideos > 0)
            <li><strong>{{ $pendingVideos }}</strong> Video Edukasi Anda sedang <span style="color: #D97706;">menunggu persetujuan</span> Admin.</li>
        @endif
        @if($rejectedVideos > 0)
            <li><strong>{{ $rejectedVideos }}</strong> Video Edukasi Anda <span style="color: #DC2626;">ditolak</span> oleh Admin. <a href="{{ route('bpp.video.index') }}" style="text-decoration: underline;">Cek sekarang</a>.</li>
        @endif
    </ul>
</div>
@endif

{{-- STATS GRID --}}
<div class="grid-4" style="margin-bottom: var(--sp-6);">
    <div class="stat-card">
        <div class="stat-icon" style="color: #2563eb; background: #dbeafe;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Personil Penyuluh</div>
            <div class="stat-value">{{ $penyuluhCount }}</div>
            <a href="{{ route('bpp.penyuluh.index') }}" style="font-size:0.75rem;color:var(--color-primary);text-decoration:none;">Lihat Daftar <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="color: #ea580c; background: #ffedd5;">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Berita & Kegiatan</div>
            <div class="stat-value">{{ $newsCount }}</div>
            <a href="{{ route('bpp.berita.index') }}" style="font-size:0.75rem;color:var(--color-primary);text-decoration:none;">Lihat Berita <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="color: #9333ea; background: #f3e8ff;">
            <i class="fa-solid fa-file-invoice"></i>
        </div>
        <div class="stat-content">
            <div class="stat-title">Permintaan Laporan</div>
            <div class="stat-value">{{ $activeRequests }}</div>
            <a href="{{ route('bpp.laporan.index') }}" style="font-size:0.75rem;color:var(--color-primary);text-decoration:none;">Upload Laporan <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</div>

{{-- QUICK ACTIONS --}}
<h3 style="margin-bottom: var(--sp-4);">Aksi Cepat</h3>
<div class="grid-2" style="gap: var(--sp-4);">
    <a href="{{ route('bpp.berita.create') }}" class="btn btn-outline" style="text-align: left; padding: var(--sp-4); justify-content: flex-start; gap: var(--sp-3);">
        <div style="width: 40px; height: 40px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text);">
            <i class="fa-solid fa-pen-nib"></i>
        </div>
        <div>
            <div style="font-weight: 600; color: var(--color-text);">Tulis Berita Baru</div>
            <div style="font-size: 0.8rem; color: var(--color-text-muted);">Kirimkan berita/kegiatan untuk persetujuan admin.</div>
        </div>
    </a>
    
    <a href="{{ route('bpp.laporan.index') }}" class="btn btn-outline" style="text-align: left; padding: var(--sp-4); justify-content: flex-start; gap: var(--sp-3);">
        <div style="width: 40px; height: 40px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--color-text);">
            <i class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <div>
            <div style="font-weight: 600; color: var(--color-text);">Upload Laporan Monev</div>
            <div style="font-size: 0.8rem; color: var(--color-text-muted);">Kirim laporan bulanan, triwulan, dsb dari penyuluh Anda.</div>
        </div>
    </a>
</div>

@endif
@endsection
