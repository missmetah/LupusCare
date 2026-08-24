@extends('layouts.app')

@section('title', 'Mes symptômes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('symptoms.create') }}" class="btn-lc btn">+ Enregistrer aujourd'hui</a>
</div>

<div class="lc-card">
    @forelse($logs as $log)
    <a href="{{ route('symptoms.show', $log) }}" style="text-decoration:none;color:inherit">
        <div class="py-3 border-bottom" style="cursor:pointer">

            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="fw-semibold small">{{ $log->logged_at->format('d M Y') }}</span>
                    @if($log->flare_up)
                    <span class="badge ms-2" style="background:#FEE2E2;color:#991B1B;font-size:10px">⚠ Poussée</span>
                    @endif
                </div>
            </div>

            {{-- Échelles --}}
            <div class="row g-2 mb-2">
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

            {{-- Symptômes cochés --}}
            @if($log->symptoms && count($log->symptoms) > 0)
            <div class="d-flex flex-wrap gap-1 mb-2">
                @foreach($log->symptoms as $symptom)
                <span class="badge" style="background:#EDE9FE;color:#5B21B6;font-size:11px">{{ $symptom }}</span>
                @endforeach
            </div>
            @endif

            {{-- Notes --}}
            @if($log->notes)
            <div class="text-muted small">{{ $log->notes }}</div>
            @endif

            {{-- Commentaire médecin --}}
            @if($log->doctor_comment)
            <div class="mt-2 p-2 rounded-2" style="background:#F0FDF4;border-left:3px solid #059669">
                <div style="font-size:11px;color:#059669;font-weight:500">Commentaire du médecin</div>
                <div class="small">{{ $log->doctor_comment }}</div>
            </div>
            @endif

        </div>
    </a>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-activity" style="font-size:2rem"></i>
        <p class="mt-2">Aucun symptôme enregistré.</p>
        <a href="{{ route('symptoms.create') }}" class="btn-lc btn mt-2">Commencer le journal</a>
    </div>
    @endforelse
</div>

{{ $logs->links() }}
@endsection