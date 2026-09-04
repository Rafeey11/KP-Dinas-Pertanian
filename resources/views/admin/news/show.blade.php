@extends('layouts.app')
@section('title', 'Review Berita')
@section('header_title', 'Review Persetujuan Berita')
@section('content')
<div style="margin-bottom:var(--sp-6);"><a href="{{ route('admin.news.index') }}" style="color:var(--color-primary);text-decoration:none;font-size:0.85rem;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Berita</a></div>

@if(session('success'))<div style="margin-bottom:var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif

<div class="grid-2" style="gap:var(--sp-6);align-items:start;">
    {{-- Tampilan Berita --}}
    <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);box-shadow:var(--shadow-sm);">
        <span class="badge" style="background:#E5E7EB;color:#374151;margin-bottom:var(--sp-2);">{{ $news->category->name ?? 'Kategori' }}</span>
        <h2 style="margin:0 0 var(--sp-4);line-height:1.3;">{{ $news->title }}</h2>
        <div style="display:flex;gap:var(--sp-4);font-size:0.85rem;color:var(--color-text-muted);margin-bottom:var(--sp-6);border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-4);">
            <div><i class="fa-solid fa-building-user"></i> {{ $news->bpp->name ?? 'BPP' }}</div>
            <div><i class="fa-solid fa-user-pen"></i> {{ $news->penyuluh->name ?? $news->creator->name }}</div>
            <div><i class="fa-solid fa-calendar"></i> {{ $news->activity_date ? $news->activity_date->format('d M Y') : '-' }}</div>
        </div>
        
        <div style="line-height:1.6;color:var(--color-text);">
            {!! $news->content !!}
        </div>
    </div>

    {{-- Form Keputusan Kanan --}}
    <div>
        <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);box-shadow:var(--shadow-sm);position:sticky;top:24px;">
            <h3 style="margin:0 0 var(--sp-4);font-size:1.1rem;border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-2);">Keputusan Publikasi</h3>
            
            <div style="margin-bottom:var(--sp-4);">
                <strong>Status Saat Ini:</strong> 
                @if($news->status==='pending_approval')<span class="badge badge-warning">Menunggu Persetujuan</span>
                @elseif($news->status==='published')<span class="badge badge-success">Telah Dipublikasikan</span>
                @elseif($news->status==='rejected')<span class="badge badge-danger">Ditolak</span>@endif
            </div>

            @if($news->status === 'pending_approval')
                <form action="{{ route('admin.news.approve', $news) }}" method="POST" style="margin-bottom:var(--sp-4);">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:10px;background:#16A34A;border-color:#16A34A;" class="form-delete"><i class="fa-solid fa-check"></i> Setujui & Publikasikan</button>
                </form>

                <hr style="border:none;border-top:1px solid var(--color-border-light);margin:var(--sp-4) 0;">

                <form action="{{ route('admin.news.reject', $news) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:var(--sp-3);">
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:4px;">Alasan Penolakan</label>
                        <textarea name="admin_notes" rows="3" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;resize:vertical;" placeholder="Tulis alasan mengapa berita ditolak..."></textarea>
                    </div>
                    <button type="submit" class="btn" style="width:100%;padding:10px;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;" class="form-delete"><i class="fa-solid fa-xmark"></i> Tolak Kiriman</button>
                </form>
            @else
                <div style="background:#F9FAFB;padding:var(--sp-4);border-radius:var(--rounded);font-size:0.85rem;">
                    @if($news->status === 'rejected')
                        <strong style="color:#991B1B;">Alasan Penolakan:</strong><br>
                        {{ $news->admin_notes ?? 'Tidak ada catatan.' }}
                    @else
                        Keputusan telah dibuat untuk berita ini.
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
