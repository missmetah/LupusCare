@extends('layouts.app')

@section('title', 'Nouveau rendez-vous')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="lc-card">
            <h6 class="mb-4">
                @if(auth()->user()->isDoctor())
                    Planifier un rendez-vous pour un patient
                @else
                    Demander un rendez-vous
                @endif
            </h6>

            <form method="POST" action="{{ route('rendezvous.store') }}" id="rdvForm">
                @csrf

                @if(auth()->user()->isDoctor())
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Patient</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">-- Choisir un patient --</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Date et heure</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control"
                           value="{{ old('scheduled_at') }}" required>
                </div>

                @else
                {{-- MÉDECIN --}}
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Médecin</label>
                    <select name="doctor_id" class="form-select" id="doctorSelect" required>
                        <option value="">-- Choisir un médecin --</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}"
                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->name }}
                            @if($doctor->doctorProfile?->specialty) — {{ $doctor->doctorProfile->specialty }} @endif
                            @if($doctor->doctorProfile?->clinic_name) ({{ $doctor->doctorProfile->clinic_name }}) @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- CALENDRIER --}}
                <div class="mb-3" id="calendarSection" style="display:none">
                    <label class="form-label small fw-semibold">Choisir une date</label>

                    {{-- Légende --}}
                    <div class="d-flex gap-3 mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <div style="width:12px;height:12px;border-radius:3px;background:#7C3AED"></div>
                            <span style="font-size:11px;color:#6B7280">Disponible</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <div style="width:12px;height:12px;border-radius:3px;background:#E5E7EB"></div>
                            <span style="font-size:11px;color:#6B7280">Non disponible</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <div style="width:12px;height:12px;border-radius:3px;background:#EDE9FE;border:2px solid #7C3AED"></div>
                            <span style="font-size:11px;color:#6B7280">Sélectionné</span>
                        </div>
                    </div>

                    {{-- Navigation mois --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <button type="button" id="prevMonth" class="btn btn-sm"
                                style="background:#EDE9FE;color:#7C3AED;border:none;border-radius:8px">
                            ←
                        </button>
                        <span id="monthLabel" class="fw-semibold small"></span>
                        <button type="button" id="nextMonth" class="btn btn-sm"
                                style="background:#EDE9FE;color:#7C3AED;border:none;border-radius:8px">
                            →
                        </button>
                    </div>

                    {{-- Grille calendrier --}}
                    <div id="calendarGrid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;text-align:center">
                        @foreach(['Lu','Ma','Me','Je','Ve','Sa','Di'] as $j)
                        <div style="font-size:11px;color:#6B7280;font-weight:600;padding:4px">{{ $j }}</div>
                        @endforeach
                    </div>

                    <input type="hidden" name="date_selected" id="dateSelected">
                </div>

                {{-- Créneaux --}}
                <div class="mb-3" id="creneauxSection" style="display:none">
                    <label class="form-label small fw-semibold">Créneau disponible</label>
                    <div id="creneauxList" class="d-flex flex-wrap gap-2 mt-1"></div>
                    <input type="hidden" name="scheduled_at" id="scheduledAt">
                    <div id="noCreneaux" class="text-muted small mt-1" style="display:none">
                        Aucun créneau disponible pour cette date.
                    </div>
                </div>
                @endif

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Motif (optionnel)</label>
                    <textarea name="reason" class="form-control" rows="3"
                              placeholder="Décrivez brièvement le motif...">{{ old('reason') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-lc btn">
                        @if(auth()->user()->isDoctor()) Planifier @else Envoyer la demande @endif
                    </button>
                    <a href="{{ route('rendezvous.index') }}" class="btn btn-light">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

@if(auth()->user()->isPatient())
<script>
    const doctorSelect    = document.getElementById('doctorSelect');
    const calendarSection = document.getElementById('calendarSection');
    const creneauxSection = document.getElementById('creneauxSection');
    const creneauxList    = document.getElementById('creneauxList');
    const noCreneaux      = document.getElementById('noCreneaux');
    const scheduledAt     = document.getElementById('scheduledAt');
    const dateSelected    = document.getElementById('dateSelected');
    const monthLabel      = document.getElementById('monthLabel');
    const calendarGrid    = document.getElementById('calendarGrid');

    let currentDate     = new Date();
    let joursDispo      = [];

    const mois = ['Janvier','Février','Mars','Avril','Mai','Juin',
                  'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

    doctorSelect.addEventListener('change', function () {
        if (!this.value) return;
        calendarSection.style.display = 'block';
        creneauxSection.style.display = 'none';
        chargerJoursDispo(this.value);
    });

  function chargerJoursDispo(doctorId) {
    fetch(`/api/jours-dispo?doctor_id=${doctorId}`)
        .then(r => r.json())
        .then(dates => {
            joursDispo = dates; // maintenant c'est un tableau de dates 'Y-m-d'
            renderCalendar();
        });
}

function renderCalendar() {
    const cells = calendarGrid.querySelectorAll('.day-cell');
    cells.forEach(c => c.remove());

    monthLabel.textContent = mois[currentDate.getMonth()] + ' ' + currentDate.getFullYear();

    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay  = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    let startOffset = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1;

    for (let i = 0; i < startOffset; i++) {
        const empty = document.createElement('div');
        empty.className = 'day-cell';
        calendarGrid.appendChild(empty);
    }

    const today = new Date();
    today.setHours(0,0,0,0);

    for (let d = 1; d <= lastDay.getDate(); d++) {
        const date    = new Date(currentDate.getFullYear(), currentDate.getMonth(), d);
        const dateStr = date.toISOString().split('T')[0];
        const dispo   = joursDispo.includes(dateStr); // compare avec dates précises
        const passe   = date <= today;
        const selected = selectedDate === dateStr;

        const cell = document.createElement('div');
        cell.className = 'day-cell';
        cell.textContent = d;
        cell.style.cssText = `
            padding: 8px 4px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: ${dispo && !passe ? '600' : '400'};
            cursor: ${dispo && !passe ? 'pointer' : 'default'};
            background: ${selected ? '#EDE9FE' : dispo && !passe ? '#F5F3FF' : 'transparent'};
            color: ${selected ? '#5B21B6' : dispo && !passe ? '#7C3AED' : '#9CA3AF'};
            border: ${selected ? '2px solid #7C3AED' : dispo && !passe ? '1.5px solid #DDD6FE' : 'none'};
            transition: all .15s;
        `;

        if (dispo && !passe) {
            cell.addEventListener('click', () => selectDate(dateStr, cell));
            cell.addEventListener('mouseover', () => {
                if (selectedDate !== dateStr) cell.style.background = '#EDE9FE';
            });
            cell.addEventListener('mouseout', () => {
                if (selectedDate !== dateStr) cell.style.background = '#F5F3FF';
            });
        }

        calendarGrid.appendChild(cell);
    }
}

    function selectDate(dateStr, cell) {
        selectedDate = dateStr;
        dateSelected.value = dateStr;
        renderCalendar();
        chargerCreneaux(doctorSelect.value, dateStr);
    }

    function chargerCreneaux(doctorId, date) {
        creneauxList.innerHTML = '<span class="text-muted small">Chargement...</span>';
        creneauxSection.style.display = 'block';
        noCreneaux.style.display = 'none';
        scheduledAt.value = '';

        fetch(`/api/creneaux?doctor_id=${doctorId}&date=${date}`)
            .then(r => r.json())
            .then(creneaux => {
                creneauxList.innerHTML = '';
                if (creneaux.length === 0) {
                    noCreneaux.style.display = 'block';
                    return;
                }
                creneaux.forEach(c => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = c;
                    btn.style.cssText = 'border:1.5px solid #7C3AED;color:#7C3AED;background:white;border-radius:8px;padding:6px 14px;font-size:13px;cursor:pointer;transition:all .15s';
                    btn.onclick = () => {
                        creneauxList.querySelectorAll('button').forEach(b => {
                            b.style.background = 'white';
                            b.style.color = '#7C3AED';
                        });
                        btn.style.background = '#7C3AED';
                        btn.style.color = 'white';
                        scheduledAt.value = date + ' ' + c + ':00';
                    };
                    creneauxList.appendChild(btn);
                });
            });
    }

    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Déclencher automatiquement si un médecin est déjà sélectionné
    if (doctorSelect.value) {
        calendarSection.style.display = 'block';
        chargerJoursDispo(doctorSelect.value);
    }


</script>
@endif
@endsection