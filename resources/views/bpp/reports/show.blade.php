@extends('layouts.app')
@section('title', 'Daftar Penyuluh: ' . $reportRequest->title)
@section('header_title', 'Status Pengumpulan Laporan')
@section('content')
<div style="margin-bottom:var(--sp-6);"><a href="{{ route('bpp.laporan.index') }}" style="color:var(--color-primary);text-decoration:none;font-size:0.85rem;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Permintaan</a></div>

@if(session('success'))<div style="margin-bottom:var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif

<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);margin-bottom:var(--sp-6);">
    <h3 style="margin:0 0 var(--sp-2);">{{ $reportRequest->title }}</h3>
    <p style="color:var(--color-text-muted);margin:0 0 var(--sp-4);">{{ $reportRequest->reportType->name }} — Periode: {{ $reportRequest->period }} {{ $reportRequest->period_year }} — Deadline: <strong style="{{ $reportRequest->isOverdue() ? 'color:#DC2626;' : '' }}">{{ $reportRequest->deadline->format('d M Y') }}</strong></p>
    @if($reportRequest->description)<p style="font-size:0.9rem;margin-bottom:var(--sp-4);">{{ $reportRequest->description }}</p>@endif
    <div style="background:#F9FAFB;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">
        <strong>Format diizinkan:</strong> {{ implode(', ', $reportRequest->allowed_formats ?? []) }} | <strong>Ukuran Max:</strong> {{ number_format($reportRequest->max_file_size/1024, 1) }} MB
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-header-title">Pilih Penyuluh ({{ $bpp->name }})</h4></div>
    <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Nama Penyuluh</th><th>Status Laporan</th><th>Waktu Upload</th><th>Aksi</th></tr></thead><tbody>
    @foreach($penyuluhs as $i => $penyuluh)
        @php $sub = $submissions->get($penyuluh->id); @endphp
        <tr>
            <td>{{ $i+1 }}</td>
            <td style="font-weight:600;">{{ $penyuluh->name }}<br><span style="font-size:0.8rem;color:var(--color-text-muted);font-weight:normal;">{{ $penyuluh->nip ?? '-' }}</span></td>
            <td>
                @if(!$sub)<span class="badge" style="background:#F3F4F6;color:#4B5563;">Belum Mengirim</span>
                @elseif($sub->status==='pending')<span class="badge badge-warning">Menunggu Validasi</span>
                @elseif($sub->status==='approved')<span class="badge badge-success">Disetujui</span>
                @elseif($sub->status==='revision')<span class="badge badge-danger">Perlu Revisi</span>
                @elseif($sub->status==='rejected')<span class="badge badge-danger">Ditolak</span>@endif
            </td>
            <td><span style="font-size:0.85rem;">{{ $sub && $sub->latestVersion ? $sub->latestVersion->created_at->format('d M Y H:i') : '-' }}</span></td>
            <td>
                @if(!$sub)
                    <a href="{{ route('bpp.laporan.upload', [$reportRequest->id, $penyuluh->id]) }}" class="btn btn-sm btn-primary">Upload</a>
                @elseif($sub->status === 'revision' || $sub->status === 'rejected')
                    <a href="{{ route('bpp.laporan.upload', [$reportRequest->id, $penyuluh->id]) }}" class="btn btn-sm" style="background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;">Upload Revisi</a>
                @else
                    <a href="{{ route('bpp.laporan.upload', [$reportRequest->id, $penyuluh->id]) }}" class="btn btn-sm btn-outline">Lihat Detail</a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody></table></div></div>
@endsection
