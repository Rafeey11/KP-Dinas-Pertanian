@extends('layouts.app')

@section('title', 'Bank SOP / Data Eksternal')
@section('header_title', 'Bank SOP / Data Eksternal')

@section('content')
<div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--sp-6);">
    <h4 class="card-header-title">Daftar Bank SOP</h4>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.bank-sops.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Bank SOP
    </a>
    @endif
</div>

@if(session('success'))
<div style="margin-bottom:var(--sp-6);background:#DCFCE7;color:#166534;padding:var(--sp-3) var(--sp-4);border-radius:var(--rounded);font-size:0.85rem;">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table" style="min-width: 900px;">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Target</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($bankSops as $sop)
                    <tr>
                        <td>{{ $sop->title }}</td>
                        <td>{{ $sop->category }}</td>
                        <td>
                            @if($sop->type === 'document') <span class="badge" style="background:#F1F5F9;color:#475569;">Dokumen</span>
                            @elseif($sop->type === 'link') <span class="badge" style="background:#E0F2FE;color:#0284C7;">Link</span>
                            @elseif($sop->type === 'template') <span class="badge badge-warning">Template</span>
                            @elseif($sop->type === 'internal_form') <span class="badge badge-success">Form Internal</span>
                            @endif
                        </td>
                        <td>{{ $sop->target_type === 'all_bpp' ? 'Seluruh BPP' : 'BPP Tertentu' }}</td>
                        <td>
                            @if($sop->status === 'published') <span class="badge badge-success">Aktif</span>
                            @else <span class="badge" style="background:#F1F5F9;color:#475569;">{{ ucfirst($sop->status) }}</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 6px; align-items: center; white-space: nowrap;">
                            <a href="{{ route('admin.bank-sops.show', $sop->id) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($sop->type === 'internal_form')
                            <a href="{{ route('admin.bank-sops.monitor', $sop->id) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;" title="Monitoring">
                                <i class="fa-solid fa-chart-line"></i>
                            </a>
                            @endif
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.bank-sops.edit', $sop->id) }}" class="btn btn-sm btn-outline" style="padding:4px 10px;font-size:0.75rem;" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.bank-sops.destroy', $sop->id) }}" method="POST" style="margin: 0; display: inline-flex;" class="form-delete" onsubmit="return confirm('Yakin hapus data ini?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="padding:4px 10px;font-size:0.75rem;background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5; display: inline-flex; align-items: center; height: 26px;" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data Bank SOP</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @if($bankSops->hasPages())
    <div style="padding:var(--sp-4);border-top:1px solid var(--color-border-light);">
        {{ $bankSops->links() }}
    </div>
    @endif
</div>
@endsection
