@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')

@if(auth()->user()->isPatient())
{{-- STATS PATIENT --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Prochain RDV</div>
            <div class="fs-5 fw-semibold mt-1">
                {{ $nextRendezvous ? $nextRendezvous->scheduled_at->format('d M') : 'Aucun' }}
            </div>
            <div class="small" style="color:var(--lc-primary)">
                {{ $nextRendezvous ? 'Dr. '.$nextRendezvous->doctor->name : '—' }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">RDV ce mois</div>
            <div class="fs-4 fw-semibold mt-1">{{ $rendezvousMonth }}</div>
            <div class="small" style="color:var(--lc-primary)">
                {{ $pendingRendezvous }} en attente
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Douleur moy. (7j)</div>
            <div class="fs-4 fw-semibold mt-1">
                {{ $avgPain ? number_format($avgPain, 1) : '—' }}<span class="fs-6 fw-normal">/10</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat" style="border-left-color:#DC2626">
            <div class="text-muted small">Poussées ce mois</div>
            <div class="fs-4 fw-semibold mt-1" style="color:#DC2626">{{ $flaresMonth }}</div>
        </div>
    </div>
</div>

{{-- CONTENU PATIENT --}}
<div class="row">
    <div class="col-md-6">
        <div class="lc-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Prochains rendez-vous</h6>
                <a href="/rendezvous/create" class="btn-lc btn btn-sm">+ Nouveau</a>
            </div>
            @forelse($recentRendezvous as $rdv)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <div class="rounded-2 d-flex align-items-center justify-content-center fw-semibold"
                     style="width:38px;height:38px;background:#EDE9FE;color:#7C3AED;font-size:13px">
                    {{ strtoupper(substr($rdv->doctor->name, 0, 2)) }}
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold small">Dr. {{ $rdv->doctor->name }}</div>
                    <div class="text-muted" style="font-size:12px">
                        {{ $rdv->scheduled_at->format('d M Y — H:i') }}
                    </div>
                </div>
                <span class="badge badge-{{ $rdv->status }}">
                    {{ match($rdv->status) {
                        'confirmed'  => 'Confirmé',
                        'pending'    => 'En attente',
                        'refused'    => 'Refusé',
                        'completed'  => 'Terminé',
                        'cancelled'  => 'Annulé',
                        default      => $rdv->status
                    } }}
                </span>
            </div>
            @empty
            <p class="text-muted small mt-2">Aucun rendez-vous pour le moment.</p>
            @endforelse
        </div>
    </div>

    <div class="col-md-6">
        <div class="lc-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Journal des symptômes</h6>
                <a href="/symptoms/create" class="btn-lc btn btn-sm">+ Ajouter</a>
            </div>
            @forelse($recentSymptoms as $log)
            <div class="d-flex align-items-center gap-2 py-2 border-bottom">
                <span class="text-muted" style="font-size:11px;width:55px">
                    {{ $log->logged_at->format('d M') }}
                </span>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span style="font-size:11px;width:50px;color:#6B7280">Douleur</span>
                        <div class="flex-grow-1 rounded-pill" style="height:6px;background:#EDE9FE">
                            <div class="rounded-pill" style="height:6px;width:{{ ($log->pain_level ?? 0) * 10 }}%;background:#7C3AED"></div>
                        </div>
                        <span style="font-size:11px;width:20px">{{ $log->pain_level ?? '—' }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:11px;width:50px;color:#6B7280">Fatigue</span>
                        <div class="flex-grow-1 rounded-pill" style="height:6px;background:#EDE9FE">
                            <div class="rounded-pill" style="height:6px;width:{{ ($log->fatigue_level ?? 0) * 10 }}%;background:#A78BFA"></div>
                        </div>
                        <span style="font-size:11px;width:20px">{{ $log->fatigue_level ?? '—' }}</span>
                    </div>
                </div>
                @if($log->flare_up)
                <span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:10px">Poussée</span>
                @endif
            </div>
            @empty
            <p class="text-muted small mt-2">Aucun symptôme enregistré.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- COURBE D'ÉVOLUTION --}}
@if($chartData->count() > 1)
<div class="lc-card mt-4">
    <h6 class="mb-3">Évolution sur 14 jours</h6>
    <canvas id="evolutionChart" height="80"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $chartData->pluck('logged_at')->map(fn($d) => '"'.\Carbon\Carbon::parse($d)->format('d M').'"')->join(',') !!},
            datasets: [
                {
                    label: 'Douleur',
                    data: [{{ $chartData->pluck('pain_level')->join(',') }}],
                    borderColor: '#7C3AED',
                    backgroundColor: 'rgba(124,58,237,0.08)',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Fatigue',
                    data: [{{ $chartData->pluck('fatigue_level')->join(',') }}],
                    borderColor: '#A78BFA',
                    backgroundColor: 'rgba(167,139,250,0.08)',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Sommeil',
                    data: [{{ $chartData->pluck('sleep_quality')->join(',') }}],
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5,150,105,0.08)',
                    tension: 0.4,
                    fill: true,
                }
            ]
        },
        options: {
            scales: { y: { min: 0, max: 10, ticks: { stepSize: 2 } } },
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endif

@else
{{-- STATS MÉDECIN --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Total patients</div>
            <div class="fs-4 fw-semibold mt-1">{{ $totalPatients }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">RDV ce mois</div>
            <div class="fs-4 fw-semibold mt-1">{{ $rendezvousMonth }}</div>
            <div class="small" style="color:var(--lc-primary)">{{ $pendingRendezvous }} en attente</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">Prochain RDV</div>
            <div class="fs-5 fw-semibold mt-1">
                {{ $nextRendezvous ? $nextRendezvous->scheduled_at->format('d M') : 'Aucun' }}
            </div>
            <div class="small" style="color:var(--lc-primary)">
                {{ $nextRendezvous ? $nextRendezvous->patient->name : '—' }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="lc-card lc-card-stat">
            <div class="text-muted small">En attente</div>
            <div class="fs-4 fw-semibold mt-1" style="color:#D97706">{{ $pendingRendezvous }}</div>
        </div>
    </div>
</div>

{{-- LISTE RDV MÉDECIN --}}
<div class="lc-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Rendez-vous à venir</h6>
        <a href="/rendezvous/create" class="btn-lc btn btn-sm">+ Nouveau</a>
    </div>
    @forelse($recentRendezvous as $rdv)
    <div class="d-flex align-items-center gap-3 py-2 border-bottom">
        <div class="rounded-2 d-flex align-items-center justify-content-center fw-semibold"
             style="width:38px;height:38px;background:#EDE9FE;color:#7C3AED;font-size:13px">
            {{ strtoupper(substr($rdv->patient->name, 0, 2)) }}
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $rdv->patient->name }}</div>
            <div class="text-muted" style="font-size:12px">
                {{ $rdv->scheduled_at->format('d M Y — H:i') }}
            </div>
        </div>
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
    </div>
    @empty
    <p class="text-muted small mt-2">Aucun rendez-vous pour le moment.</p>
    @endforelse
</div>
@endif

@endsection