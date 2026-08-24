@extends('layouts.app')

@section('title', 'Symptômes de ' . $patient->name)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
         style="width:48px;height:48px;background:#EDE9FE;color:#7C3AED;font-size:16px">
        {{ strtoupper(substr($patient->name, 0, 2)) }}
    </div>
    <div>
        <h6 class="mb-0">{{ $patient->name }}</h6>
        <div class="text-muted small">{{ $patient->email }}</div>
    </div>
    <a href="{{ route('symptoms.index') }}" class="btn btn-light btn-sm ms-auto">← Retour</a>
</div>

<div class="lc-card">
    @forelse($logs as $log)
    <div class="py-3 border-bottom">

        {{-- En-tête --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="fw-semibold small">{{ $log->logged_at->format('d M Y') }}</span>
                @if($log->flare_up)
                <span class="badge ms-2" style="background:#FEE2E2;color:#991B1B">⚠ Poussée</span>
                @endif
                @if($log->frequency)
                <span class="badge ms-1" style="background:#EDE9FE;color:#5B21B6;font-size:10px">
                    {{ $log->frequency }}
                </span>
                @endif
            </div>
        </div>

        {{-- Échelles --}}
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div style="font-size:11px;color:#6B7280">Douleur</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="flex-grow-1 rounded-pill" style="height:6px;background:#EDE9FE">
                        <div class="rounded-pill" style="height:6px;width:{{ ($log->pain_level ?? 0) * 10 }}%;background:#7C3AED"></div>
                    </div>
                    <span style="font-size:12px;font-weight:500">{{ $log->pain_level ?? '—' }}/10</span>
                </div>
            </div>
            <div class="col-md-4">
                <div style="font-size:11px;color:#6B7280">Fatigue</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="flex-grow-1 rounded-pill" style="height:6px;background:#EDE9FE">
                        <div class="rounded-pill" style="height:6px;width:{{ ($log->fatigue_level ?? 0) * 10 }}%;background:#A78BFA"></div>
                    </div>
                    <span style="font-size:12px;font-weight:500">{{ $log->fatigue_level ?? '—' }}/10</span>
                </div>
            </div>
            <div class="col-md-4">
                <div style="font-size:11px;color:#6B7280">Sommeil</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="flex-grow-1 rounded-pill" style="height:6px;background:#EDE9FE">
                        <div class="rounded-pill" style="height:6px;width:{{ ($log->sleep_quality ?? 0) * 10 }}%;background:#059669"></div>
                    </div>
                    <span style="font-size:12px;font-weight:500">{{ $log->sleep_quality ?? '—' }}/10</span>
                </div>
            </div>
        </div>

        {{-- Symptômes par catégorie --}}
        @foreach($log->allSymptoms() as $category => $symptoms)
        @if(count($symptoms) > 0)
        <div class="mb-2">
            <div class="small fw-semibold mb-1" style="color:#5B21B6">{{ $category }}</div>
            <div class="d-flex flex-wrap gap-1">
                @foreach($symptoms as $symptom => $intensity)
                <span class="badge" style="font-size:11px;padding:4px 8px;
                    background:{{ $intensity === 'sévère' ? '#FEE2E2' : ($intensity === 'modéré' ? '#FEF3C7' : '#D1FAE5') }};
                    color:{{ $intensity === 'sévère' ? '#991B1B' : ($intensity === 'modéré' ? '#92400E' : '#065F46') }}">
                    {{ $symptom }} — {{ $intensity }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach

        {{-- Réponses poussée --}}
        @if($log->flare_suspected && $log->flare_answers)
        <div class="mt-2 p-2 rounded-2 mb-2" style="background:#FEF3C7;border-left:3px solid #F59E0B">
            <div class="small fw-semibold mb-1" style="color:#92400E">Questionnaire poussée</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($log->flare_answers as $key => $answer)
                @if($answer)
                <span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:11px">
                    {{ \App\Http\Controllers\SymptomLogController::FLARE_QUESTIONS[$key] ?? $key }}
                </span>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notes patient --}}
        @if($log->notes)
        <div class="text-muted small mb-2">
            <i class="bi bi-chat-left-text me-1"></i>{{ $log->notes }}
        </div>
        @endif

        {{-- Commentaire médecin --}}
        @if($log->doctor_comment)
        <div class="p-2 rounded-2 mb-2" style="background:#F0FDF4;border-left:3px solid #059669">
            <div style="font-size:11px;color:#059669;font-weight:500">Votre commentaire</div>
            <div class="small">{{ $log->doctor_comment }}</div>
        </div>
        @endif

        {{-- Formulaire commentaire médecin --}}
        <form method="POST" action="{{ route('symptoms.comment', $log) }}" class="mt-2">
            @csrf
            <div class="d-flex gap-2">
                <input type="text" name="doctor_comment" class="form-control form-control-sm"
                       placeholder="Ajouter un commentaire médical..."
                       value="{{ $log->doctor_comment ?? '' }}">
                <button class="btn-lc btn btn-sm" style="white-space:nowrap">Enregistrer</button>
            </div>
        </form>

    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-activity" style="font-size:2rem"></i>
        <p class="mt-2">Aucun symptôme enregistré pour ce patient.</p>
    </div>
    @endforelse
</div>

{{ $logs->links() }}
@endsection