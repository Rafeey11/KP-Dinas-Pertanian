@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('header_title', 'Manajemen Akun Pengguna')

@section('content')
<div style="background: #fff; border-radius: var(--rounded-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm); overflow: hidden;">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: var(--sp-4) var(--sp-6);">
        <h4 class="card-header-title">Daftar Akun Pengguna</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-user-plus"></i> Tambah Akun
        </a>
    </div>

    @if(session('error'))
        <div style="margin: var(--sp-4) var(--sp-6); background: #FEE2E2; color: #991B1B; padding: var(--sp-3); border-radius: var(--rounded); font-size: 0.85rem;">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->has('error'))
        <div style="margin: var(--sp-4) var(--sp-6); background: #FEE2E2; color: #991B1B; padding: var(--sp-3); border-radius: var(--rounded); font-size: 0.85rem;">
            {{ $errors->first('error') }}
        </div>
    @endif
    
    <div class="table-responsive">
        <table class="table" style="min-width: 800px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Role (Hak Akses)</th>
                    <th>Terhubung ke BPP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">
                            <div>{{ $user->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--color-text-muted); font-weight: normal;">{{ $user->email }}</div>
                        </td>
                        <td><span style="font-family: monospace; background: var(--color-bg); padding: 2px 6px; border-radius: 4px;">{{ $user->username }}</span></td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-primary">Administrator</span>
                            @elseif($user->role === 'admin_tim_kerja')
                                <span class="badge" style="background-color: #0ea5e9; color: white;">Admin Tim Kerja</span>
                            @else
                                <span class="badge badge-success">Operator BPP</span>
                            @endif
                        </td>
                        <td>
                            @if($user->role === 'bpp' && $user->bpp)
                                <a href="{{ route('admin.bpp.edit', $user->bpp->id) }}" style="color: var(--color-primary); text-decoration: none;">{{ $user->bpp->name }}</a>
                            @else
                                <span style="color: var(--color-text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline" style="padding: 4px 10px; font-size: 0.75rem;">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="padding: 4px 10px; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--color-text-muted); padding: var(--sp-6);">
                            Belum ada data akun yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
