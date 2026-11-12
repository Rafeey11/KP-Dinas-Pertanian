@extends('layouts.app')
@section('title', 'Detail Laporan')
@section('header_title', 'Validasi Laporan Penyuluh')
@section('content')
<div style="margin-bottom:var(--sp-6);"><a href="{{ route('admin.report-validation.index') }}" style="color:var(--color-primary);text-decoration:none;font-size:0.85rem;"><i class="fa-solid fa-arrow-left"></i> Kembali</a></div>

@if(session('success'))<div style="margin-bottom:var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>@endif

@if($errors->any())
<div style="margin-bottom:var(--sp-6);background:#FEE2E2;color:#991B1B;padding:var(--sp-3);border-radius:var(--rounded);font-size:0.85rem;">
    <ul style="margin:0;padding-left:1.5rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="grid-2" style="gap:var(--sp-6);">
    {{-- Panel Kiri: Info & Form Validasi --}}
    <div>
        <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);margin-bottom:var(--sp-6);">
            <h3 style="margin:0 0 var(--sp-4);font-size:1.1rem;border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-2);">Informasi Pengirim</h3>
            <div style="display:grid;grid-template-columns:100px 1fr;gap:var(--sp-2);font-size:0.9rem;">
                <div style="color:var(--color-text-muted);">Penyuluh</div><div style="font-weight:600;">{{ $submission->penyuluh->name }} ({{ $submission->penyuluh->nip ?? '-' }})</div>
                <div style="color:var(--color-text-muted);">BPP</div><div style="font-weight:600;">{{ $submission->bpp->name }}</div>
                <div style="color:var(--color-text-muted);">Status</div><div>
                    @if($submission->status==='pending')<span class="badge badge-warning">Menunggu</span>
                    @elseif($submission->status==='approved')<span class="badge badge-success">Disetujui</span>
                    @elseif($submission->status==='revision')<span class="badge badge-danger">Perlu Revisi</span>
                    @elseif($submission->status==='rejected')<span class="badge badge-danger">Ditolak</span>@endif
                </div>
            </div>
            
            <h3 style="margin:var(--sp-6) 0 var(--sp-4);font-size:1.1rem;border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-2);">Informasi Laporan</h3>
            <div style="display:grid;grid-template-columns:100px 1fr;gap:var(--sp-2);font-size:0.9rem;">
                <div style="color:var(--color-text-muted);">Nama</div><div style="font-weight:600;">{{ $submission->reportRequest->title }}</div>
                <div style="color:var(--color-text-muted);">Jenis</div><div>{{ $submission->reportRequest->reportType->name }}</div>
                <div style="color:var(--color-text-muted);">Periode</div><div>{{ $submission->reportRequest->period }} {{ $submission->reportRequest->period_year }}</div>
            </div>
        </div>

        <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);">
            <h3 style="margin:0 0 var(--sp-4);font-size:1.1rem;border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-2);">Form Validasi</h3>
            <form action="{{ route('admin.report-validation.validate', $submission) }}" method="POST">
                @csrf
                <div style="margin-bottom:var(--sp-4);">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Status Keputusan *</label>
                    <select name="status" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;">
                        <option value="pending" {{ $submission->status==='pending'?'selected':'' }}>Tunda Keputusan (Menunggu)</option>
                        <option value="approved" {{ $submission->status==='approved'?'selected':'' }}>Setujui Laporan</option>
                        <option value="revision" {{ $submission->status==='revision'?'selected':'' }}>Minta Revisi</option>
                        <option value="rejected" {{ $submission->status==='rejected'?'selected':'' }}>Tolak Laporan</option>
                    </select>
                </div>
                <div style="margin-bottom:var(--sp-4);">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Catatan Validasi</label>
                    <textarea name="admin_notes" rows="3" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;resize:vertical;" placeholder="Berikan catatan jika perlu revisi...">{{ old('admin_notes', $submission->admin_notes) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:10px;">Simpan Keputusan</button>
            </form>
        </div>
    </div>

    {{-- Panel Kanan: Riwayat File (Versi) --}}
    <div>
        <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);">
            <h3 style="margin:0 0 var(--sp-4);font-size:1.1rem;border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-2);">Riwayat File & Revisi</h3>
            
            <div style="display:flex;flex-direction:column;gap:var(--sp-4);">
                @foreach($submission->versions as $ver)
                <div style="border:1px solid var(--color-border);border-radius:var(--rounded);padding:var(--sp-3);background:{{ $loop->first ? '#F9FAFB' : '#fff' }};">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--sp-2);">
                        <div style="font-weight:600;font-size:0.95rem;">
                            Versi {{ $ver->version_number }} 
                            @if($loop->first)<span class="badge badge-success" style="font-size:0.6rem;padding:2px 6px;margin-left:4px;">TERBARU</span>@endif
                        </div>
                        <a href="{{ route('admin.report-validation.download', [$submission->id, $ver->id]) }}" class="btn btn-sm btn-outline" style="padding:4px 8px;font-size:0.75rem;"><i class="fa-solid fa-download"></i> Unduh File</a>
                    </div>
                    <div style="font-size:0.8rem;color:var(--color-text-muted);margin-bottom:var(--sp-2);">
                        <div><i class="fa-regular fa-file"></i> {{ $ver->file_name }} ({{ number_format($ver->file_size / 1024, 1) }} KB)</div>
                        <div><i class="fa-regular fa-clock"></i> {{ $ver->created_at->format('d M Y H:i') }} oleh {{ $ver->uploader->name ?? 'User' }}</div>
                    </div>
                    @if($ver->notes)
                    <div style="background:#F3F4F6;padding:var(--sp-2);border-radius:4px;font-size:0.8rem;color:#374151;">
                        <strong>Catatan Pengirim:</strong> {{ $ver->notes }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
