@extends('layouts.app')

@section('title', 'Tous les patients')

@section('content')
<div class="lc-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0">Tous les patients</h6>
        <a href="{{ route('admin.index') }}" class="btn btn-light btn-sm">← Retour</a>
    </div>

    @forelse($patients as $patient)
    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
             style="width:44px;height:44px;background:#EDE9FE;color:#7C3AED;font-size:14px;flex-shrink:0">
            {{ strtoupper(substr($patient->name, 0, 2)) }}
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $patient->name }}</div>
            <div class="text-muted" style="font-size:12px">{{ $patient->email }}</div>
            <div class="text-muted" style="font-size:11px">
                Inscrit le {{ $patient->created_at->format('d M Y') }}
            </div>
        </div>
        <span class="badge" style="background:#D1FAE5;color:#065F46;font-size:11px">Actif</span>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <p>Aucun patient enregistré.</p>
    </div>
    @endforelse
</div>
@endsection