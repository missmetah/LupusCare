@extends('layouts.app')

@section('title', 'Ajouter une disponibilité')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="lc-card">
            <h6 class="mb-4">Ajouter une disponibilité</h6>

            <form method="POST" action="{{ route('disponibilites.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Date</label>
                    <input type="date" name="date_disponible" class="form-control"
                           value="{{ old('date_disponible') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    @error('date_disponible')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Heure de début</label>
                        <input type="time" name="heure_debut" class="form-control"
                               value="{{ old('heure_debut') }}" required>
                        @error('heure_debut')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Heure de fin</label>
                        <input type="time" name="heure_fin" class="form-control"
                               value="{{ old('heure_fin') }}" required>
                        @error('heure_fin')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Durée par consultation</label>
                    <select name="duree_consultation" class="form-select" required>
                        <option value="15" {{ old('duree_consultation') == 15 ? 'selected' : '' }}>15 minutes</option>
                        <option value="20" {{ old('duree_consultation') == 20 ? 'selected' : '' }}>20 minutes</option>
                        <option value="30" {{ old('duree_consultation') == 30 ? 'selected' : '' }} selected>30 minutes</option>
                        <option value="45" {{ old('duree_consultation') == 45 ? 'selected' : '' }}>45 minutes</option>
                        <option value="60" {{ old('duree_consultation') == 60 ? 'selected' : '' }}>1 heure</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-lc btn">Enregistrer</button>
                    <a href="{{ route('disponibilites.index') }}" class="btn btn-light">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection