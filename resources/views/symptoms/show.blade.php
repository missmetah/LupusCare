@extends('layouts.app')

@section('title', 'Détail du symptôme')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="lc-card">

            {{-- EN-TÊTE --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="mb-0">{{ $symptomLog->logged_at->format('d M Y') }}</h6>
                    @if($symptomLog->frequency)
                    <span class="badge mt-1" style="background:#EDE9FE;color:#5B21B6;font-size:11px">
                        {{ $symptomLog->frequency }}
                    </span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    @if($symptomLog->flare_up)
                    <span class="badge" style="background:#FEE2E2;color:#991B1B">⚠ Poussée</span>
                    @endif
                    @if($symptomLog->flare_suspected)
                    <span class="badge" style="background:#FEF3C7;color:#92400E">Poussée suspectée</span>
                    @endif
                </div>
            </div>

            {{-- ÉCHELLES --}}
            <div class="lc-card mb-4" style="background:#F5F3FF;box-shadow:none">
                <div class="small fw-semibold mb-3" style="color:#5B21B6">Niveaux globaux</div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span style="color:#6B7280">Douleur</span>
                        <span style="color:#7C3AED;font-weight:500">{{ $symptomLog->pain_level ?? '—' }}/10</span>
                    </div>
                    <div class="rounded-pill" style="height:8px;background:#EDE9FE">
                        <div class="rounded-pill" style="height:8px;width:{{ ($symptomLog->pain_level ?? 0) * 10 }}%;background:#7C3AED"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span style="color:#6B7280">Fatigue</span>
                        <span style="color:#A78BFA;font-weight:500">{{ $symptomLog->fatigue_level ?? '—' }}/10</span>
                    </div>
                    <div class="rounded-pill" style="height:8px;background:#EDE9FE">
                        <div class="rounded-pill" style="height:8px;width:{{ ($symptomLog->fatigue_level ?? 0) * 10 }}%;background:#A78BFA"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span style="color:#6B7280">Sommeil</span>
                        <span style="color:#059669;font-weight:500">{{ $symptomLog->sleep_quality ?? '—' }}/10</span>
                    </div>
                    <div class="rounded-pill" style="height:8px;background:#EDE9FE">
                        <div class="rounded-pill" style="height:8px;width:{{ ($symptomLog->sleep_quality ?? 0) * 10 }}%;background:#059669"></div>
                    </div>
                </div>
            </div>

            {{-- SYMPTÔMES PAR CATÉGORIE --}}
            @php $allSymptoms = $symptomLog->allSymptoms(); @endphp
            @if(count($allSymptoms) > 0)
            <div class="mb-4">
                <div class="small fw-semibold mb-3" style="color:#5B21B6">Symptômes signalés</div>
                @foreach($allSymptoms as $category => $symptoms)
                @if(count($symptoms) > 0)
                <div class="mb-3">
                    <div class="small fw-semibold mb-2 text-muted">{{ $category }}</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($symptoms as $symptom => $intensity)
                        <span class="badge" style="font-size:12px;padding:5px 10px;
                            background:{{ $intensity === 'sévère' ? '#FEE2E2' : ($intensity === 'modéré' ? '#FEF3C7' : '#D1FAE5') }};
                            color:{{ $intensity === 'sévère' ? '#991B1B' : ($intensity === 'modéré' ? '#92400E' : '#065F46') }}">
                            {{ $symptom }}
                            <span class="ms-1 fw-normal">— {{ $intensity }}</span>
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif

            {{-- RÉPONSES POUSSÉE --}}
            @if($symptomLog->flare_suspected && $symptomLog->flare_answers)
            <div class="mb-4 p-3 rounded-2" style="background:#FEF3C7;border-left:3px solid #F59E0B">
                <div class="small fw-semibold mb-2" style="color:#92400E">Questionnaire poussée</div>
                <div class="row g-2">
                    @foreach($symptomLog->flare_answers as $key => $answer)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 small">
                            <span style="color:{{ $answer ? '#DC2626' : '#6B7280' }}">
                                {{ $answer ? '✓' : '✗' }}
                            </span>
                            <span style="color:{{ $answer ? '#DC2626' : '#6B7280' }}">
                                {{ \App\Http\Controllers\SymptomLogController::FLARE_QUESTIONS[$key] ?? $key }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- NOTES PATIENT --}}
            @if($symptomLog->notes)
            <div class="mb-4">
                <div class="small fw-semibold mb-2" style="color:#5B21B6">Notes personnelles</div>
                <div class="p-3 rounded-2 small" style="background:#F5F3FF">
                    {{ $symptomLog->notes }}
                </div>
            </div>
            @endif

            {{-- COMMENTAIRE MÉDECIN --}}
            @if($symptomLog->doctor_comment)
            <div class="mb-4">
                <div class="small fw-semibold mb-2" style="color:#059669">Commentaire du médecin</div>
                <div class="p-3 rounded-2 small" style="background:#F0FDF4;border-left:3px solid #059669">
                    {{ $symptomLog->doctor_comment }}
                </div>
            </div>
            @endif

            <a href="{{ route('symptoms.index') }}" class="btn btn-light btn-sm">← Retour</a>
        </div>
    </div>
</div>
@endsection