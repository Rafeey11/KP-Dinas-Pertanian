@extends('layouts.app')

@section('title', 'Bank SOP / Bank Data')
@section('header_title', 'Bank SOP / Bank Data')

@section('content')
<div class="mb-4">
    <h2 class="h4 mb-0">Daftar Bank SOP</h2>
    <p class="text-muted">Dokumen, panduan, form internal, dan tautan penting yang dibagikan oleh Admin CWS.</p>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Batas Waktu</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bankSops as $sop)
                    <tr>
                        <td>{{ $sop->title }}</td>
                        <td>{{ $sop->category }}</td>
                        <td>
                            @if($sop->type === 'document') <span class="badge bg-secondary">Dokumen</span>
                            @elseif($sop->type === 'link') <span class="badge bg-info">Link</span>
                            @elseif($sop->type === 'template') <span class="badge bg-warning">Template</span>
                            @elseif($sop->type === 'internal_form') <span class="badge bg-success">Form Internal</span>
                            @endif
                        </td>
                        <td>{{ $sop->deadline ? $sop->deadline->format('d M Y H:i') : '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('bpp.bank-sops.show', $sop->id) }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-folder-open"></i> Buka
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada Bank SOP yang tersedia untuk BPP Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bankSops->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $bankSops->links() }}
    </div>
    @endif
</div>
@endsection
