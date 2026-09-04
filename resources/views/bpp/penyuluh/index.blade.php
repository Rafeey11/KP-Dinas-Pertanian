@extends('layouts.app')

@section('title', 'Personil Penyuluh')
@section('header_title', 'Manajemen Personil Penyuluh')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">Daftar Penyuluh - {{ $bpp->name }}</h4>
        <a href="{{ route('bpp.penyuluh.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Penyuluh
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table" style="min-width: 800px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Penyuluh</th>
                    <th>Jabatan</th>
                    <th>Wilayah Binaan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyuluhs as $index => $penyuluh)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span style="font-family: monospace; background: var(--color-bg); padding: 2px 6px; border-radius: 4px;">{{ $penyuluh->nip }}</span></td>
                        <td style="font-weight: 600;">{{ $penyuluh->name }}</td>
                        <td>
                            <div>{{ $penyuluh->position }}</div>
                            <div style="font-size: 0.8rem; color: var(--color-text-muted);">{{ $penyuluh->employment_status }}</div>
                        </td>
                        <td>
                            <div>{{ $penyuluh->working_area ?: '-' }}</div>
                            @if($penyuluh->village_area)
                                <div style="font-size: 0.8rem; color: var(--color-primary);">{{ $penyuluh->village_area }}</div>
                            @endif
                        </td>
                        <td>
                            @if($penyuluh->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">Nonaktif</span>
                            @endif
                            @if($penyuluh->is_public)
                                <span class="badge badge-info" style="margin-top: 4px;">Tampil Publik</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('bpp.penyuluh.edit', $penyuluh->id) }}" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('bpp.penyuluh.destroy', $penyuluh->id) }}" method="POST" style="display: inline-block;" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="padding: 4px 10px; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: var(--sp-6);">
                            Belum ada data penyuluh yang ditambahkan di BPP ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
