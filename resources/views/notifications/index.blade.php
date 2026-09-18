@extends('layouts.app')

@section('title', 'Notifikasi')
@section('header_title', 'Notifikasi')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-header-title">Notifikasi Anda</h2>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">Tandai Semua Dibaca</button>
        </form>
        @endif
    </div>
    <div class="card-body" style="padding: 0;">
        @forelse($notifications as $notification)
            <div class="notification-item {{ empty($notification->read_at) ? 'unread' : '' }}" style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-border); display: flex; gap: 1rem; align-items: flex-start; transition: var(--transition);">
                <div class="notification-icon" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ empty($notification->read_at) ? 'var(--color-primary-lighter)' : 'var(--color-bg)' }}; color: {{ empty($notification->read_at) ? 'var(--color-primary)' : 'var(--color-text-muted)' }}; flex-shrink: 0;">
                    @if($notification->data['module'] ?? '' == 'Laporan')
                        <i class="fa-solid fa-file-invoice"></i>
                    @elseif($notification->data['module'] ?? '' == 'Berita')
                        <i class="fa-solid fa-newspaper"></i>
                    @elseif($notification->data['module'] ?? '' == 'Bank SOP')
                        <i class="fa-solid fa-folder-open"></i>
                    @else
                        <i class="fa-solid fa-bell"></i>
                    @endif
                </div>
                <div class="notification-content" style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem;">
                        <h4 style="margin: 0; font-size: 0.95rem; font-weight: {{ empty($notification->read_at) ? '700' : '600' }}; color: var(--color-text);">
                            {{ $notification->data['title'] ?? 'Notifikasi' }}
                        </h4>
                        <span style="font-size: 0.75rem; color: var(--color-text-muted); white-space: nowrap;">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--color-text-secondary); line-height: 1.4;">
                        {{ $notification->data['message'] ?? '' }}
                    </p>
                    @if(isset($notification->data['action_url']))
                        <div style="margin-top: 0.5rem;">
                            <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-primary" style="font-size: 0.75rem; padding: 0.25rem 0.75rem;">Lihat Detail</a>
                        </div>
                    @endif
                </div>
                @if(empty($notification->read_at))
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-primary); margin-top: 0.5rem; flex-shrink: 0;"></div>
                @endif
            </div>
        @empty
            <div style="padding: 3rem; text-align: center; color: var(--color-text-muted);">
                <i class="fa-regular fa-bell-slash" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Belum ada notifikasi saat ini.</p>
            </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
    <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--color-border);">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<style>
    .notification-item:hover {
        background: var(--color-bg);
    }
    .notification-item.unread {
        background: rgba(30, 64, 175, 0.02);
    }
</style>
@endsection
