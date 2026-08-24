@extends('layouts.app')

@section('title', 'Enregistrer mes symptômes')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="lc-card">
            <h6 class="mb-4">Comment vous sentez-vous aujourd'hui ?</h6>

            <form method="POST" action="{{ route('symptoms.store') }}">
                @csrf

                {{-- DATE ET FRÉQUENCE --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Date</label>
                        <input type="date" name="logged_at" class="form-control"
                               value="{{ old('logged_at', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Fréquence</label>
                        <select name="frequency" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            <option value="aujourd'hui" {{ old('frequency') == "aujourd'hui" ? 'selected' : '' }}>Aujourd'hui seulement</option>
                            <option value="plusieurs_jours" {{ old('frequency') == 'plusieurs_jours' ? 'selected' : '' }}>Depuis plusieurs jours</option>
                            <option value="occasionnel" {{ old('frequency') == 'occasionnel' ? 'selected' : '' }}>Occasionnel</option>
                            <option value="permanent" {{ old('frequency') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                        </select>
                    </div>
                </div>

                {{-- ÉCHELLES --}}
                <div class="lc-card mb-4" style="background:#F5F3FF;box-shadow:none">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Niveau de douleur global</label>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted small">0</span>
                            <input type="range" name="pain_level" min="0" max="10"
                                   value="{{ old('pain_level', 0) }}" class="form-range flex-grow-1"
                                   oninput="document.getElementById('painVal').innerText = this.value">
                            <span class="text-muted small">10</span>
                            <span class="fw-bold" style="color:#7C3AED;min-width:24px" id="painVal">{{ old('pain_level', 0) }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Niveau de fatigue</label>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted small">0</span>
                            <input type="range" name="fatigue_level" min="0" max="10"
                                   value="{{ old('fatigue_level', 0) }}" class="form-range flex-grow-1"
                                   oninput="document.getElementById('fatigueVal').innerText = this.value">
                            <span class="text-muted small">10</span>
                            <span class="fw-bold" style="color:#A78BFA;min-width:24px" id="fatigueVal">{{ old('fatigue_level', 0) }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Qualité du sommeil</label>
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted small">0</span>
                            <input type="range" name="sleep_quality" min="0" max="10"
                                   value="{{ old('sleep_quality', 0) }}" class="form-range flex-grow-1"
                                   oninput="document.getElementById('sleepVal').innerText = this.value">
                            <span class="text-muted small">10</span>
                            <span class="fw-bold" style="color:#059669;min-width:24px" id="sleepVal">{{ old('sleep_quality', 0) }}</span>
                        </div>
                    </div>
                </div>

                {{-- SYMPTÔMES PAR CATÉGORIE --}}
                @foreach($symptoms as $key => $category)
                <div class="mb-4">
                    <div class="fw-semibold small mb-2 pb-1" style="border-bottom:2px solid #EDE9FE;color:#5B21B6">
                        {{ $category['label'] }}
                    </div>
                    <div class="row g-2">
                        @foreach($category['items'] as $item)
                        @php $fieldKey = 'sym_' . $key . '_' . md5($item); @endphp
                        <div class="col-md-6">
                            <div class="p-2 rounded-2" style="border:.5px solid #EDE9FE">
                                <div class="small fw-semibold mb-1">{{ $item }}</div>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach(['aucun' => 'Aucun', 'léger' => 'Léger', 'modéré' => 'Modéré', 'sévère' => 'Sévère'] as $value => $label)
                                    <div class="form-check form-check-inline m-0">
                                        <input class="form-check-input" type="radio"
                                               name="{{ $fieldKey }}"
                                               id="{{ $fieldKey }}_{{ $value }}"
                                               value="{{ $value }}"
                                               {{ old($fieldKey, 'aucun') === $value ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="{{ $fieldKey }}_{{ $value }}"
                                               style="color:{{ $value === 'sévère' ? '#DC2626' : ($value === 'modéré' ? '#D97706' : ($value === 'léger' ? '#059669' : '#6B7280')) }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- BOUTON POUSSÉE --}}
                <div class="mb-4 p-3 rounded-2" style="background:#FEF3C7;border:1px solid #FCD34D">
                    <div class="fw-semibold mb-3" style="color:#92400E">
                        ⚠ Je pense faire une poussée de lupus
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="flare_suspected"
                               value="1" id="flareSuspected" {{ old('flare_suspected') ? 'checked' : '' }}
                               onchange="document.getElementById('flareQuestions').style.display = this.checked ? 'block' : 'none'">
                        <label class="form-check-label small fw-semibold" for="flareSuspected">
                            Cochez si vous suspectez une poussée
                        </label>
                    </div>
                    <div id="flareQuestions" style="display:{{ old('flare_suspected') ? 'block' : 'none' }}">
                        <div class="row g-2">
                            @foreach($flareQuestions as $key => $question)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="flare_{{ $key }}" value="1"
                                           id="flare_{{ $key }}"
                                           {{ old('flare_' . $key) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="flare_{{ $key }}">
                                        {{ $question }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- NOTES --}}
                <div class="mb-4">
                    <label class="form-label small fw-semibold">Notes personnelles (optionnel)</label>
                    <textarea name="notes" class="form-control" rows="3"
                              placeholder="Décrivez ce que vous ressentez...">{{ old('notes') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-lc btn">Enregistrer</button>
                    <a href="{{ route('symptoms.index') }}" class="btn btn-light">Annuler</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection