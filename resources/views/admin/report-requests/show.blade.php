@extends('layouts.app')
@section('title', 'Progress: ' . $reportRequest->title)
@section('header_title', 'Progress Pengumpulan Laporan')
@section('content')
<div style="margin-bottom:var(--sp-6);">
    <a href="{{ route('admin.report-requests.index') }}" style="color:var(--color-primary);text-decoration:none;font-size:0.85rem;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Permintaan</a>
</div>

{{-- Summary Card --}}
<div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);margin-bottom:var(--sp-6);">
    <h3 style="margin:0 0 var(--sp-2);">{{ $reportRequest->title }}</h3>
    <p style="color:var(--color-text-muted);margin:0 0 var(--sp-4);">{{ $reportRequest->reportType->name }} — Periode: {{ $reportRequest->period }} {{ $reportRequest->period_year }} — Deadline: <strong style="{{ $reportRequest->isOverdue() ? 'color:#DC2626;' : '' }}">{{ $reportRequest->deadline->format('d M Y') }}</strong></p>
    @if($reportRequest->description)<p style="font-size:0.9rem;margin-bottom:var(--sp-4);">{{ $reportRequest->description }}</p>@endif
    <div class="grid-2" style="gap:var(--sp-4);">
        <div style="display:flex;gap:var(--sp-3);flex-wrap:wrap;">
            <div style="background:var(--color-bg);padding:var(--sp-3) var(--sp-4);border-radius:var(--rounded-lg);text-align:center;min-width:100px;"><div style="font-size:1.5rem;font-weight:700;color:var(--color-primary);">{{ $totalPenyuluh }}</div><div style="font-size:0.75rem;color:var(--color-text-muted);">Total Penyuluh</div></div>
            <div style="background:var(--color-bg);padding:var(--sp-3) var(--sp-4);border-radius:var(--rounded-lg);text-align:center;min-width:100px;"><div style="font-size:1.5rem;font-weight:700;color:#2563EB;">{{ $totalSubmitted }}</div><div style="font-size:0.75rem;color:var(--color-text-muted);">Sudah Kirim</div></div>
            <div style="background:#DCFCE7;padding:var(--sp-3) var(--sp-4);border-radius:var(--rounded-lg);text-align:center;min-width:100px;"><div style="font-size:1.5rem;font-weight:700;color:#166534;">{{ $totalApproved }}</div><div style="font-size:0.75rem;color:#166534;">Disetujui</div></div>
            <div style="background:#FEF3C7;padding:var(--sp-3) var(--sp-4);border-radius:var(--rounded-lg);text-align:center;min-width:100px;"><div style="font-size:1.5rem;font-weight:700;color:#92400E;">{{ $totalPending }}</div><div style="font-size:0.75rem;color:#92400E;">Menunggu</div></div>
            <div style="background:#FEE2E2;padding:var(--sp-3) var(--sp-4);border-radius:var(--rounded-lg);text-align:center;min-width:100px;"><div style="font-size:1.5rem;font-weight:700;color:#991B1B;">{{ $totalRevision }}</div><div style="font-size:0.75rem;color:#991B1B;">Perlu Revisi</div></div>
        </div>
    </div>
</div>

{{-- Progress per BPP --}}
<div class="card">
    <div class="card-header"><h4 class="card-header-title">Progress Per BPP</h4></div>
    <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Nama BPP</th><th>Total Penyuluh</th><th>Sudah Kirim</th><th>Disetujui</th><th>Progress</th></tr></thead><tbody>
    @foreach($bpps as $i => $bpp)
    <tr>
        <td>{{ $i+1 }}</td>
        <td style="font-weight:600;">{{ $bpp->name }}</td>
        <td>{{ $bpp->penyuluhs_count }}</td>
        <td>{{ $bpp->submitted_count }}</td>
        <td>{{ $bpp->approved_count }}</td>
        <td>
            @php $pct = $bpp->penyuluhs_count > 0 ? round($bpp->submitted_count / $bpp->penyuluhs_count * 100) : 0; @endphp
            <div style="background:#E5E7EB;border-radius:999px;height:20px;width:120px;overflow:hidden;display:inline-block;vertical-align:middle;">
                <div style="background:{{ $pct >= 100 ? '#16A34A' : '#2563EB' }};height:100%;width:{{ $pct }}%;border-radius:999px;transition:width 0.3s;"></div>
            </div>
            <span style="font-size:0.8rem;margin-left:var(--sp-2);font-weight:600;">{{ $pct }}%</span>
        </td>
    </tr>
    @endforeach
    </tbody></table></div></div>
@endsection
