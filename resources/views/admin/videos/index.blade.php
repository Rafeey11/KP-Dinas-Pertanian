@extends('layouts.app')
@section('title', 'Galeri Video')
@section('header_title', 'Kelola Video Edukasi')
@section('content')
<div class="card">
    <div class="card-header"><h4 class="card-header-title">Daftar Video</h4><a href="{{ route('admin.videos.create') }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i> Tambah Video</a></div>
    @if(session('success'))<div style="margin:var(--sp-4) var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif
    <div class="table-responsive"><table class="table"><thead><tr><th>Order</th><th>Judul Video</th><th>Pengirim (BPP)</th><th>Tampil Beranda</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($videos as $i => $v)
    <tr @if($v->status === 'pending') style="background: #FEF9C3;" @endif>
        <td style="text-align:center;width:60px;">{{ $v->sort_order }}</td>
        <td>
            <div style="font-weight:600;"><a href="{{ $v->video_url }}" target="_blank" style="color:var(--color-primary);text-decoration:none;">{{ Str::limit($v->title, 50) }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.75rem;"></i></a></div>
            <div style="font-size:0.8rem;color:var(--color-text-muted);">{{ Str::limit($v->description, 50) }}</div>
        </td>
        <td>
            {{ $v->bpp->name ?? 'Pusat/Admin' }}
            @if($v->bpp_id && $v->status === 'pending')
                <br><span style="font-size: 0.75rem; color: #B45309; font-weight: bold;"><i class="fa-solid fa-clock"></i> Pengajuan Baru</span>
            @endif
        </td>
        <td>@if($v->show_on_homepage)<span class="badge badge-info">Ya</span>@else<span class="badge" style="background:#E5E7EB;color:#374151;">Tidak</span>@endif</td>
        <td>
            @if($v->status==='active')<span class="badge badge-success">Aktif</span>
            @elseif($v->status==='pending')<span class="badge badge-warning">Pending</span>
            @elseif($v->status==='rejected')<span class="badge" style="background:#FEE2E2;color:#991B1B;">Ditolak</span>
            @else<span class="badge" style="background:#FEE2E2;color:#991B1B;">Nonaktif</span>@endif
        </td>
        <td>
            @if($v->status === 'pending')
                <form action="{{ route('admin.videos.approve', $v) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui video ini?');"> 
                    @csrf 
                    <button type="submit" class="btn btn-sm btn-success" style="padding:4px 10px;font-size:0.75rem;" title="Setujui"><i class="fa-solid fa-check"></i></button>
                </form>
                <form action="{{ route('admin.videos.reject', $v) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menolak video ini?');"> 
                    @csrf 
                    <button type="submit" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;" title="Tolak"><i class="fa-solid fa-xmark"></i></button>
                </form>
            @endif
            
            <a href="{{ route('admin.videos.edit', $v) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;" title="Edit"><i class="fa-solid fa-pen"></i></a>
            
            <form action="{{ route('admin.videos.destroy', $v) }}" method="POST" style="display:inline-block;" class="form-delete"> 
                @csrf 
                @method('DELETE') 
                <button type="submit" class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
            </form>
        </td>
    </tr>
    @empty <tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Belum ada video.</td></tr>@endforelse
    </tbody></table></div></div>
@endsection
