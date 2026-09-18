@extends('layouts.app')

@section('title', 'Audit Log')
@section('header_title', 'Sistem Audit Log')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-header-title">Filter Log</h2>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <label class="form-label">Modul</label>
                <select name="module" class="form-control">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Aksi</label>
                <select name="action" class="form-control">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div>
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-header-title">Daftar Audit Log</h2>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>Keterangan</th>
                    <th>IP / Browser</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $log->user->name ?? 'System' }}</div>
                        <div style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $log->user->role ?? '-' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background: var(--color-surface-alt); color: var(--color-text);">{{ $log->module }}</span>
                    </td>
                    <td>
                        @php
                            $actionColor = match($log->action) {
                                'created', 'published', 'approved' => 'bg-green-100 text-green-800',
                                'updated', 'submitted', 'revision_requested' => 'bg-blue-100 text-blue-800',
                                'deleted', 'deactivated', 'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="badge {{ $actionColor }}">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td style="max-width: 300px; white-space: normal;">
                        {{ $log->description }}
                    </td>
                    <td>
                        <div style="font-size: 0.8rem;">{{ $log->ip_address }}</div>
                        <div style="font-size: 0.7rem; color: var(--color-text-muted); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">
                            {{ $log->user_agent }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Belum ada data log aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="p-4 border-t" style="border-top: 1px solid var(--color-border);">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<style>
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .bg-green-100 { background-color: #dcfce7; }
    .text-green-800 { color: #166534; }
    .bg-blue-100 { background-color: #dbeafe; }
    .text-blue-800 { color: #1e40af; }
    .bg-red-100 { background-color: #fee2e2; }
    .text-red-800 { color: #991b1b; }
    .bg-gray-100 { background-color: #f3f4f6; }
    .text-gray-800 { color: #1f2937; }
</style>
@endsection
