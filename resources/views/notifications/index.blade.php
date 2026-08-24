@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="lc-card">
    @forelse($notifications as $notification)
    <a href="/rendezvous" style="text-decoration:none;color:inherit">
        <div class="d-flex align-items-start gap-3 py-3 border-bottom {{ is_null($notification->read_at) ? '' : 'opacity-75' }}"
             style="cursor:pointer">
            <div class="rounded-circle d-flex align-items-center justify-content-center"
                 style="width:38px;height:38px;background:#EDE9FE;color:#7C3AED;font-size:16px;flex-shrink:0">
                <i class="bi bi-bell"></i>
            </div>
            <div class="flex-grow-1">
                <div class="small fw-semibold">{{ $notification->data['message'] }}</div>
                <div class="text-muted" style="font-size:12px">
                    {{ isset($notification->data['scheduled_at'])
                        ? \Carbon\Carbon::parse($notification->data['scheduled_at'])->format('d M Y — H:i')
                        : '' }}
                </div>
                <div class="text-muted" style="font-size:11px">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>
            @if(is_null($notification->read_at))
            <span class="badge rounded-pill" style="background:#EDE9FE;color:#7C3AED;font-size:10px">Nouveau</span>
            @endif
        </div>
    </a>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bell-slash" style="font-size:2rem"></i>
        <p class="mt-2">Aucune notification pour le moment.</p>
    </div>
    @endforelse
</div>

{{ $notifications->links() }}
@endsection