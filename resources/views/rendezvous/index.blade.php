@extends('layouts.app')

@section('title', 'Rendez-vous')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    @if(auth()->user()->isPatient())
    <a href="{{ route('rendezvous.create') }}" class="btn-lc btn">+ Nouveau rendez-vous</a>
    @endif
</div>

<div class="lc-card">
    @forelse($rendezvous as $rdv)
    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
        <div class="rounded-2 d-flex align-items-center justify-content-center fw-semibold"
             style="width:42px;height:42px;background:#EDE9FE;color:#7C3AED;font-size:13px;flex-shrink:0">
            @if(auth()->user()->isPatient())
                {{ strtoupper(substr($rdv->doctor->name, 0, 2)) }}
            @else
                {{ strtoupper(substr($rdv->patient->name, 0, 2)) }}
            @endif
        </div>

        <div class="flex-grow-1">
            <div class="fw-semibold small">
                @if(auth()->user()->isPatient())
                    Dr. {{ $rdv->doctor->name }}
                @else
                    {{ $rdv->patient->name }}
                @endif
            </div>
            <div class="text-muted" style="font-size:12px">
                {{ $rdv->scheduled_at->format('d M Y — H:i') }}
            </div>
            @if($rdv->reason)
            <div class="text-muted mt-1" style="font-size:12px">
                <i class="bi bi-chat-left-text"></i> {{ $rdv->reason }}
            </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-{{ $rdv->status }}">
                {{ match($rdv->status) {
                    'confirmed' => 'Confirmé',
                    'pending'   => 'En attente',
                    'refused'   => 'Refusé',
                    'completed' => 'Terminé',
                    'cancelled' => 'Annulé',
                    default     => $rdv->status
                } }}
            </span>

            @if(auth()->user()->isDoctor() && $rdv->status === 'pending')
            <form method="POST" action="{{ route('rendezvous.status', $rdv) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button class="btn btn-sm" style="background:#D1FAE5;color:#065F46;border:none;border-radius:8px">
                    Confirmer
                </button>
            </form>
            <form method="POST" action="{{ route('rendezvous.status', $rdv) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="refused">
                <button class="btn btn-sm" style="background:#FEE2E2;color:#991B1B;border:none;border-radius:8px">
                    Refuser
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-calendar-x" style="font-size:2rem"></i>
        <p class="mt-2">Aucun rendez-vous pour le moment.</p>
        @if(auth()->user()->isPatient())
        <a href="{{ route('rendezvous.create') }}" class="btn-lc btn mt-2">Prendre un rendez-vous</a>
        @endif
    </div>
    @endforelse
</div>
@endsection