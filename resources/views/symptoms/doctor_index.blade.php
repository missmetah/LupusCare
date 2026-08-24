@extends('layouts.app')

@section('title', 'Mes patients')

@section('content')
<div class="lc-card">
    <h6 class="mb-4">Liste de mes patients</h6>

    @forelse($patients as $patient)
    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
             style="width:42px;height:42px;background:#EDE9FE;color:#7C3AED;font-size:14px;flex-shrink:0">
            {{ strtoupper(substr($patient->name, 0, 2)) }}
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $patient->name }}</div>
            <div class="text-muted" style="font-size:12px">{{ $patient->email }}</div>
        </div>
        <div class="d-flex gap-2">
    <a href="{{ route('symptoms.patient', $patient) }}" class="btn-lc btn btn-sm">
        Symptômes
    </a>
    <a href="{{ route('suivi.index', $patient) }}" class="btn btn-sm"
       style="background:#EDE9FE;color:#5B21B6;border:none;border-radius:10px">
        Journal de suivi
    </a>
</div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-people" style="font-size:2rem"></i>
        <p class="mt-2">Aucun patient enregistré.</p>
    </div>
    @endforelse
</div>
@endsection