@extends('layouts.app')
@section('title', 'Upload Laporan')
@section('header_title', 'Upload Laporan Penyuluh')
@section('content')
<div style="margin-bottom:var(--sp-6);"><a href="{{ route('bpp.laporan.show', $reportRequest->id) }}" style="color:var(--color-primary);text-decoration:none;font-size:0.85rem;"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Penyuluh</a></div>

<div class="grid-2" style="gap:var(--sp-6);">
    {{-- Form Upload Kiri --}}
    <div>
        <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);margin-bottom:var(--sp-6);">
            <h3 style="margin:0 0 var(--sp-2);font-size:1.1rem;">Laporan: {{ $reportRequest->title }}</h3>
            <div style="color:var(--color-text-muted);font-size:0.9rem;margin-bottom:var(--sp-4);">Penyuluh: <strong style="color:var(--color-text);">{{ $penyuluh->name }}</strong></div>
            
            @if($submission && $submission->status === 'approved')
                <div style="background:#DCFCE7;color:#166534;padding:var(--sp-4);border-radius:var(--rounded);text-align:center;">
                    <i class="fa-solid fa-circle-check" style="font-size:2rem;margin-bottom:8px;"></i>
                    <h4 style="margin:0 0 4px;">Laporan Telah Disetujui</h4>
                    <p style="margin:0;font-size:0.85rem;">Anda tidak perlu mengupload ulang laporan ini.</p>
                </div>
            @else
                @if($submission && $submission->status === 'revision')
                    <div style="background:#FEE2E2;color:#991B1B;padding:var(--sp-4);border-radius:var(--rounded);margin-bottom:var(--sp-4);">
                        <h4 style="margin:0 0 8px;font-size:1rem;"><i class="fa-solid fa-triangle-exclamation"></i> Laporan Membutuhkan Revisi</h4>
                        <p style="margin:0;font-size:0.85rem;"><strong>Catatan Admin:</strong> {{ $submission->admin_notes ?? 'Tidak ada catatan khusus.' }}</p>
                    </div>
                @endif

                @if($errors->any())<div style="background:#FEE2E2;color:#991B1B;padding:var(--sp-3);border-radius:var(--rounded);margin-bottom:var(--sp-4);"><ul style="margin:0;padding-left:1.5rem;font-size:0.85rem;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

                <form action="{{ route('bpp.laporan.store-upload', [$reportRequest->id, $penyuluh->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom:var(--sp-4);">
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Pilih File Laporan *</label>
                        <input type="file" name="file" required style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;background:#F9FAFB;" accept="{{ implode(',', array_map(fn($f) => '.'.$f, $reportRequest->allowed_formats ?? [])) }}">
                        <div style="font-size:0.75rem;color:var(--color-text-muted);margin-top:4px;">Format: {{ implode(', ', $reportRequest->allowed_formats ?? []) }} | Maks: {{ number_format($reportRequest->max_file_size/1024, 1) }} MB</div>
                    </div>
                    <div style="margin-bottom:var(--sp-4);">
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:var(--sp-2);">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" style="width:100%;padding:var(--sp-2);border:1px solid var(--color-border);border-radius:var(--rounded);font-family:inherit;resize:vertical;" placeholder="Tuliskan keterangan jika ada..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:10px;"><i class="fa-solid fa-cloud-arrow-up"></i> Upload {{ $submission ? 'Revisi' : 'Laporan' }}</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Riwayat Versi Kanan --}}
    <div>
        <div style="background:#fff;border-radius:var(--rounded-lg);border:1px solid var(--color-border);padding:var(--sp-6);">
            <h3 style="margin:0 0 var(--sp-4);font-size:1.1rem;border-bottom:1px solid var(--color-border-light);padding-bottom:var(--sp-2);">Riwayat Upload</h3>
            @if(!$submission || $submission->versions->isEmpty())
                <div style="text-align:center;padding:var(--sp-6);color:var(--color-text-muted);">Belum ada laporan yang diupload.</div>
            @else
                <div style="display:flex;flex-direction:column;gap:var(--sp-4);">
                    @foreach($submission->versions as $ver)
                    <div style="border:1px solid var(--color-border);border-radius:var(--rounded);padding:var(--sp-3);background:{{ $loop->first ? '#F9FAFB' : '#fff' }};">
                        <div style="font-weight:600;font-size:0.95rem;margin-bottom:var(--sp-2);">
                            Versi {{ $ver->version_number }}
                            @if($loop->first)
                                @if($submission->status==='pending')<span class="badge badge-warning" style="font-size:0.6rem;padding:2px 6px;margin-left:4px;">Menunggu Validasi</span>
                                @elseif($submission->status==='approved')<span class="badge badge-success" style="font-size:0.6rem;padding:2px 6px;margin-left:4px;">Disetujui</span>
                                @elseif($submission->status==='revision')<span class="badge badge-danger" style="font-size:0.6rem;padding:2px 6px;margin-left:4px;">Revisi</span>@endif
                            @endif
                        </div>
                        <div style="font-size:0.8rem;color:var(--color-text-muted);margin-bottom:var(--sp-2);">
                            <div><i class="fa-regular fa-file"></i> {{ $ver->file_name }} ({{ number_format($ver->file_size / 1024, 1) }} KB)</div>
                            <div><i class="fa-regular fa-clock"></i> {{ $ver->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
