@extends('layouts.app')

@section('title', 'Tous les médecins')

@section('content')
<div class="lc-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0">Tous les médecins</h6>
        <a href="{{ route('admin.index') }}" class="btn btn-light btn-sm">← Retour</a>
    </div>

    @forelse($doctors as $doctor)
    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
             style="width:44px;height:44px;background:#EDE9FE;color:#7C3AED;font-size:14px;flex-shrink:0">
            {{ strtoupper(substr($doctor->name, 0, 2)) }}
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $doctor->name }}</div>
            <div class="text-muted" style="font-size:12px">{{ $doctor->email }}</div>
            @if($doctor->doctorProfile?->specialty)
            <div class="text-muted" style="font-size:11px">
                {{ $doctor->doctorProfile->specialty }}
                @if($doctor->doctorProfile?->clinic_name)
                — {{ $doctor->doctorProfile->clinic_name }}
                @endif
            </div>
            @endif
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="font-size:11px;padding:5px 10px;
                background:{{ $doctor->status === 'active' ? '#D1FAE5' : ($doctor->status === 'pending' ? '#FEF3C7' : '#FEE2E2') }};
                color:{{ $doctor->status === 'active' ? '#065F46' : ($doctor->status === 'pending' ? '#92400E' : '#991B1B') }}">
                {{ $doctor->status === 'active' ? 'Actif' : ($doctor->status === 'pending' ? 'En attente' : 'Refusé') }}
            </span>
            @if($doctor->status === 'pending')
            <form method="POST" action="{{ route('admin.doctors.status', $doctor) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="active">
                <button class="btn btn-sm" style="background:#D1FAE5;color:#065F46;border:none;border-radius:8px">
                    ✓ Valider
                </button>
            </form>
            @endif
            @if($doctor->status === 'active')
            <form method="POST" action="{{ route('admin.doctors.status', $doctor) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="refused">
                <button class="btn btn-sm" style="background:#FEE2E2;color:#991B1B;border:none;border-radius:8px">
                    Suspendre
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <p>Aucun médecin enregistré.</p>
    </div>
    @endforelse
</div>
@endsection