@extends('layouts.app')

@section('title', 'Note de suivi — ' . $patient->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="lc-card">

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold"
                     style="width:44px;height:44px;background:#EDE9FE;color:#7C3AED;font-size:15px">
                    {{ strtoupper(substr($patient->name, 0, 2)) }}
                </div>
                <div>
                    <h6 class="mb-0">Note de suivi pour {{ $patient->name }}</h6>
                    <div class="text-muted small">{{ $patient->email }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('suivi.store', $patient) }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Date de consultation</label>
                        <input type="date" name="consultation_date" class="form-control"
                               value="{{ old('consultation_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Rendez-vous associé (optionnel)</label>
                        <select name="rendezvous_id" class="form-select">
                            <option value="">-- Aucun --</option>
                            @foreach($rendezvous as $rdv)
                            <option value="{{ $rdv->id }}" {{ old('rendezvous_id') == $rdv->id ? 'selected' : '' }}>
                                {{ $rdv->scheduled_at->format('d M Y — H:i') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- ACTIVITÉ DE LA MALADIE --}}
                <div class="mb-4">
                    <label class="form-label small fw-semibold">Niveau d'activité de la maladie</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach([1 => 'Rémission', 2 => 'Faible', 3 => 'Modérée', 4 => 'Élevée', 5 => 'Très élevée'] as $value => $label)
                        <div class="form-check form-check-inline m-0">
                            <input class="form-check-input" type="radio"
                                   name="disease_activity" value="{{ $value }}"
                                   id="activity_{{ $value }}"
                                   {{ old('disease_activity') == $value ? 'checked' : '' }} required>
                            <label class="form-check-label small fw-semibold px-2 py-1 rounded-2" for="activity_{{ $value }}"
                                   style="background:{{ ['','#D1FAE5','#ECFCCB','#FEF3C7','#FEE2E2','#FEE2E2'][$value] }};
                                          color:{{ ['','#059669','#65A30D','#D97706','#DC2626','#7F1D1D'][$value] }}">
                                {{ $value }} — {{ $label }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('disease_activity')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- RÉSUMÉ --}}
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Résumé de la consultation</label>
                    <textarea name="consultation_summary" class="form-control" rows="4"
                              placeholder="Observations cliniques, état général du patient...">{{ old('consultation_summary') }}</textarea>
                </div>

                {{-- TRAITEMENT --}}
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Traitement prescrit / modifié</label>
                    <textarea name="treatment" class="form-control" rows="3"
                              placeholder="Médicaments, dosages, changements...">{{ old('treatment') }}</textarea>
                </div>

                {{-- PROCHAINE ÉTAPE --}}
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Prochaine étape recommandée</label>
                    <textarea name="next_steps" class="form-control" rows="2"
                              placeholder="Examens à faire, prochain RDV, conseils...">{{ old('next_steps') }}</textarea>
                </div>

                {{-- COMMENTAIRE SYMPTÔMES --}}
                <div class="mb-4">
                    <label class="form-label small fw-semibold">Commentaire sur les symptômes signalés</label>
                    <textarea name="symptom_comment" class="form-control" rows="2"
                              placeholder="Analyse des symptômes rapportés par le patient...">{{ old('symptom_comment') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-lc btn">Enregistrer la note</button>
                    <a href="{{ route('suivi.index', $patient) }}" class="btn btn-light">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection