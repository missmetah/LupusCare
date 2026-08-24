@extends('layouts.app')

@section('title', 'Administration')

@section('content')

{{-- STATS --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Médecins en attente</div>
            <div class="fs-4 fw-semibold mt-1" style="color:#D97706">{{ $pendingDoctors->count() }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Médecins actifs</div>
            <div class="fs-4 fw-semibold mt-1" style="color:#059669">{{ $activeDoctors }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Total patients</div>
            <div class="fs-4 fw-semibold mt-1" style="color:#7C3AED">{{ $totalPatients }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Médecins refusés</div>
            <div class="fs-4 fw-semibold mt-1" style="color:#DC2626">{{ $refusedDoctors }}</div>
        </div>
    </div>
</div>

{{-- MÉDECINS EN ATTENTE --}}
<div class="lc-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0">Médecins en attente de validation</h6>
        <a href="{{ route('admin.doctors') }}" class="btn-lc btn btn-sm">Tous les médecins</a>
    </div>

    @forelse($pendingDoctors as $doctor)
    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
             style="width:44px;height:44px;background:#FEF3C7;color:#D97706;font-size:14px;flex-shrink:0">
            {{ strtoupper(substr($doctor->name, 0, 2)) }}
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $doctor->name }}</div>
            <div class="text-muted" style="font-size:12px">{{ $doctor->email }}</div>
            <div class="text-muted" style="font-size:11px">
                Inscrit le {{ $doctor->created_at->format('d M Y à H:i') }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('admin.doctors.status', $doctor) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="active">
                <button class="btn btn-sm fw-semibold"
                        style="background:#D1FAE5;color:#065F46;border:none;border-radius:8px;padding:6px 14px">
                    ✓ Valider
                </button>
            </form>
            <form method="POST" action="{{ route('admin.doctors.status', $doctor) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="refused">
                <button class="btn btn-sm fw-semibold"
                        style="background:#FEE2E2;color:#991B1B;border:none;border-radius:8px;padding:6px 14px">
                    ✗ Refuser
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-check-circle" style="font-size:2rem;color:#059669"></i>
        <p class="mt-2">Aucun médecin en attente de validation.</p>
    </div>
    @endforelse
</div>

@endsectionsss