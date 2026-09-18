@extends('layouts.app')

@section('title', 'Data BPP')
@section('header_title', auth()->user()->role === 'admin_tim_kerja' ? 'Manajemen Data BPP Binaan' : 'Manajemen Data BPP')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">{{ auth()->user()->role === 'admin_tim_kerja' ? 'Daftar BPP Binaan' : 'Daftar BPP di Kabupaten Garut' }}</h4>
        <div style="display: flex; gap: var(--sp-2);">
            <a href="{{ route('admin.bpp.export') }}" class="btn btn-sm btn-outline">
                <i class="fa-solid fa-file-csv"></i> Download CSV
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.bpp.create') }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah BPP
            </a>
            @endif
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table" style="min-width: 800px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama BPP</th>
                    <th>Kecamatan</th>
                    <th>Kontak / Telp</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bpps as $index => $bpp)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><span style="font-family: monospace; background: var(--color-bg); padding: 2px 6px; border-radius: 4px;">{{ $bpp->code }}</span></td>
                        <td style="font-weight: 600;">{{ $bpp->name }}</td>
                        <td>{{ $bpp->district }}</td>
                        <td>
                            <div>{{ $bpp->contact_person }}</div>
                            <div style="font-size: 0.8rem; color: var(--color-text-muted);">{{ $bpp->phone }}</div>
                        </td>
                        <td>
                            @if($bpp->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-warning">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.bpp.edit', $bpp->id) }}" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('admin.bpp.destroy', $bpp->id) }}" method="POST" style="display: inline-block;" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="padding: 4px 10px; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-text-muted); padding: var(--sp-6);">
                            Belum ada data BPP yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
