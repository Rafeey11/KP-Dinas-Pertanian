@extends('layouts.app')
@section('title', 'Permintaan Laporan')
@section('header_title', 'Daftar Permintaan Laporan')
@section('content')
<div class="card">
    <div class="card-header"><h4 class="card-header-title">Permintaan Laporan Aktif</h4></div>
    <div class="table-responsive"><table class="table"><thead><tr><th>Judul Permintaan</th><th>Jenis Laporan</th><th>Periode</th><th>Deadline</th><th>Progress {{ $bpp->name }}</th><th>Aksi</th></tr></thead><tbody>
    @forelse($requests as $req)
    <tr>
        <td style="font-weight:600;">{{ $req->title }}</td>
        <td>{{ $req->reportType->name ?? '-' }}</td>
        <td>{{ $req->period }} {{ $req->period_year }}</td>
        <td>
            <span style="{{ $req->isOverdue() ? 'color:#DC2626;font-weight:600;' : '' }}">{{ $req->deadline->format('d M Y') }}</span>
            @if($req->isOverdue())<br><span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:0.7rem;">Lewat Deadline</span>@endif
        </td>
        <td>
            <div style="font-size:0.8rem;margin-bottom:2px;">{{ $req->submitted }} / {{ $req->total_penyuluh }} Penyuluh ({{ $req->total_penyuluh > 0 ? round($req->submitted/$req->total_penyuluh*100) : 0 }}%)</div>
            <div style="background:#E5E7EB;border-radius:999px;height:6px;width:100%;overflow:hidden;">
                <div style="background:#2563EB;height:100%;width:{{ $req->total_penyuluh > 0 ? ($req->submitted/$req->total_penyuluh*100) : 0 }}%;"></div>
            </div>
        </td>
        <td><a href="{{ route('bpp.laporan.show', $req) }}" class="btn btn-sm btn-primary">Lihat & Upload</a></td>
    </tr>
    @empty <tr><td colspan="6" style="text-align:center;color:var(--color-text-muted);padding:var(--sp-6);">Belum ada permintaan laporan.</td></tr>@endforelse
    </tbody></table></div></div>
@endsection
