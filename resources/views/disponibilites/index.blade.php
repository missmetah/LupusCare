@extends('layouts.app')

@section('title', 'Mes disponibilités')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('disponibilites.create') }}" class="btn-lc btn">+ Ajouter une disponibilité</a>
</div>

<div class="lc-card">
    @forelse($disponibilites as $dispo)
    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
        <div class="rounded-2 d-flex align-items-center justify-content-center fw-semibold"
             style="width:56px;height:56px;background:#EDE9FE;color:#7C3AED;font-size:12px;flex-shrink:0;text-align:center;line-height:1.3">
            {{ $dispo->date_disponible->format('d') }}<br>
            <span style="font-size:10px">{{ $dispo->date_disponible->format('M') }}</span>
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold small">{{ $dispo->date_disponible->translatedFormat('l d F Y') }}</div>
            <div class="text-muted" style="font-size:12px">
                {{ $dispo->heure_debut }} — {{ $dispo->heure_fin }}
            </div>
            <div class="text-muted" style="font-size:11px">
                Consultation de {{ $dispo->duree_consultation }} min
                — {{ count($dispo->genererCreneaux()) }} créneaux
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:#D1FAE5;color:#065F46;font-size:11px">Actif</span>
            <form method="POST" action="{{ route('disponibilites.destroy', $dispo) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm"
                        style="background:#FEE2E2;color:#991B1B;border:none;border-radius:8px"
                        onclick="return confirm('Supprimer cette disponibilité ?')">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="bi bi-calendar-x" style="font-size:2rem"></i>
        <p class="mt-2">Aucune disponibilité définie.</p>
        <a href="{{ route('disponibilites.create') }}" class="btn-lc btn mt-2">
            Ajouter une disponibilité
        </a>
    </div>
    @endforelse
</div>
@endsection