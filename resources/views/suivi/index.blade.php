@extends('layouts.app')

@section('title', 'Journal de suivi — ' . $patient->name)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
         style="width:48px;height:48px;background:#EDE9FE;color:#7C3AED;font-size:16px">
        {{ strtoupper(substr($patient->name, 0, 2)) }}
    </div>
    <div>
        <h6 class="mb-0">{{ $patient->name }}</h6>
        <div class="text-muted small">Journal de suivi médical</div>
    </div>
    @if(auth()->user()->isDoctor())
    <a href="{{ route('suivi.create', $patient) }}" class="btn-lc btn btn-sm ms-auto">
        + Nouvelle note
    </a>
    @endif
</div>

<div class="lc-card">
    @forelse($suivis as $suivi)
    <div class="py-4 border-bottom">

        {{-- EN-TÊTE --}}
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <span class="fw-semibold small">{{ $suivi->consultation_date->format('d M Y') }}</span>
                <span class="text-muted small ms-2">Dr. {{ $suivi->doctor->name }}</span>
            </div>
            <span class="badge fw-semibold" style="font-size:12px;padding:5px 10px;
                background:{{ ['','#D1FAE5','#ECFCCB','#FEF3C7','#FEE2E2','#FEE2E2'][$suivi->disease_activity] }};
                color:{{ $suivi->activityColor() }}">
                Activité : {{ $suivi->activityLabel() }}
            </span>
        </div>

        {{-- RÉSUMÉ --}}
        @if($suivi->consultation_summary)
        <div class="mb-3">
            <div class="small fw-semibold mb-1" style="color:#5B21B6">Résumé de consultation</div>
            <div class="small p-3 rounded-2" style="background:#F5F3FF">
                {{ $suivi->consultation_summary }}
            </div>
        </div>
        @endif

        {{-- TRAITEMENT --}}
        @if($suivi->treatment)
        <div class="mb-3">
            <div class="small fw-semibold mb-1" style="color:#5B21B6">Traitement</div>
            <div class="small p-3 rounded-2" style="background:#F5F3FF">
                {{ $suivi->treatment }}
            </div>
        </div>
        @endif

        {{-- PROCHAINE ÉTAPE --}}
        @if($suivi->next_steps)
        <div class="mb-3">
            <div class="small fw-semibold mb-1" style="color:#059669">Prochaine étape</div>
            <div class="small p-3 rounded-2" style="background:#F0FDF4;border-left:3px solid #059669">
                {{ $suivi->next_steps }}
            </div>
        </div>
        @endif

        {{-- COMMENTAIRE SYMPTÔMES --}}
        @if($suivi->symptom_comment)
        <div class="mb-2">
            <div class="small fw-semibold mb-1" style="color:#D97706">Commentaire sur les symptômes</div>
            <div class="small p-3 rounded-2" style="background:#FFFBEB;border-left:3px solid #F59E0B">
                {{ $suivi->symptom_comment }}
            </div>
        </div>
        @endif

    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-journal-medical" style="font-size:2rem"></i>
        <p class="mt-2">Aucune note de suivi pour le moment.</p>
        @if(auth()->user()->isDoctor())
        <a href="{{ route('suivi.create', $patient) }}" class="btn-lc btn mt-2">
            Créer la première note
        </a>
        @endif
    </div>
    @endforelse
</div>

{{ $suivis->links() }}
@endsection