<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SymptomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SymptomLogController extends Controller
{
    const SYMPTOMS = [
        'general' => [
            'label' => 'Symptômes généraux',
            'items' => [
                'Fatigue', 'Fièvre', 'Perte de poids',
                "Perte d'appétit", 'Faiblesse générale',
            ],
        ],
        'pain' => [
            'label' => 'Douleurs',
            'items' => [
                'Douleurs articulaires', 'Gonflement des articulations',
                'Raideur matinale', 'Douleurs musculaires',
                'Douleur thoracique', 'Douleur abdominale', 'Maux de tête',
            ],
        ],
        'skin' => [
            'label' => 'Peau',
            'items' => [
                'Éruption en forme de papillon', 'Rougeurs',
                'Sensibilité au soleil', 'Plaques cutanées',
                'Chute de cheveux', 'Ulcères dans la bouche',
                'Ulcères du nez',
            ],
        ],
        'kidney' => [
            'label' => 'Reins',
            'items' => [
                'Urines mousseuses', 'Sang dans les urines',
                'Gonflement des jambes', 'Gonflement des pieds',
                'Gonflement du visage', "Diminution de la quantité d'urine",
            ],
        ],
        'respiratory' => [
            'label' => 'Système respiratoire',
            'items' => [
                'Essoufflement', 'Toux', 'Douleur à la respiration',
            ],
        ],
        'cardiovascular' => [
            'label' => 'Cardiovasculaire',
            'items' => [
                'Palpitations', 'Douleur à la poitrine',
                'Gonflement des chevilles',
            ],
        ],
        'neurological' => [
            'label' => 'Neurologique',
            'items' => [
                'Vertiges', 'Confusion', 'Difficulté de concentration',
                'Troubles de la mémoire', 'Convulsions',
                'Engourdissement des mains ou des pieds', 'Picotements',
            ],
        ],
        'eyes' => [
            'label' => 'Yeux',
            'items' => [
                'Vision floue', 'Yeux secs', 'Douleur oculaire',
            ],
        ],
        'digestive' => [
            'label' => 'Digestif',
            'items' => [
                'Nausées', 'Vomissements', 'Diarrhée', 'Constipation',
            ],
        ],
    ];

    const FLARE_QUESTIONS = [
        'fatigue'        => 'Fatigue inhabituelle ?',
        'fever'          => 'Fièvre ?',
        'joint_pain'     => 'Douleurs articulaires ?',
        'redness'        => 'Rougeurs ?',
        'hair_loss'      => 'Chute importante de cheveux ?',
        'swelling'       => 'Gonflement ?',
        'foamy_urine'    => 'Urines mousseuses ?',
        'chest_pain'     => 'Douleur thoracique ?',
        'breathlessness' => 'Essoufflement ?',
    ];

    public function index()
    {
        $user = Auth::user();

        if ($user->isDoctor()) {
            $patients = User::where('role', 'patient')->get();
            return view('symptoms.doctor_index', compact('patients'));
        }

        $logs = SymptomLog::where('patient_id', $user->id)
                          ->orderBy('logged_at', 'desc')
                          ->paginate(10);

        return view('symptoms.index', compact('logs'));
    }

    public function patientLogs(User $patient)
    {
        if (!Auth::user()->isDoctor()) abort(403);

        $logs = SymptomLog::where('patient_id', $patient->id)
                          ->orderBy('logged_at', 'desc')
                          ->paginate(10);

        return view('symptoms.patient_logs', compact('logs', 'patient'));
    }

    public function create()
    {
        $symptoms       = self::SYMPTOMS;
        $flareQuestions = self::FLARE_QUESTIONS;
        return view('symptoms.create', compact('symptoms', 'flareQuestions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'logged_at'     => 'required|date',
            'pain_level'    => 'required|integer|min:0|max:10',
            'fatigue_level' => 'required|integer|min:0|max:10',
            'sleep_quality' => 'required|integer|min:0|max:10',
            'frequency'     => 'required|string',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $symptomsData = [];
        foreach (self::SYMPTOMS as $key => $category) {
            $symptomsData[$key] = [];
            foreach ($category['items'] as $item) {
                $fieldKey  = 'sym_' . $key . '_' . md5($item);
                $intensity = $request->input($fieldKey, 'aucun');
                if ($intensity !== 'aucun') {
                    $symptomsData[$key][$item] = $intensity;
                }
            }
        }

        $flareAnswers = [];
        $flareCount   = 0;
        foreach (self::FLARE_QUESTIONS as $key => $question) {
            $answer = $request->boolean('flare_' . $key);
            $flareAnswers[$key] = $answer;
            if ($answer) $flareCount++;
        }

        $flareSuspected = $request->boolean('flare_suspected');

        SymptomLog::create([
            'patient_id'              => Auth::id(),
            'logged_at'               => $request->logged_at,
            'pain_level'              => $request->pain_level,
            'fatigue_level'           => $request->fatigue_level,
            'sleep_quality'           => $request->sleep_quality,
            'frequency'               => $request->frequency,
            'symptoms_general'        => $symptomsData['general'],
            'symptoms_pain'           => $symptomsData['pain'],
            'symptoms_skin'           => $symptomsData['skin'],
            'symptoms_kidney'         => $symptomsData['kidney'],
            'symptoms_respiratory'    => $symptomsData['respiratory'],
            'symptoms_cardiovascular' => $symptomsData['cardiovascular'],
            'symptoms_neurological'   => $symptomsData['neurological'],
            'symptoms_eyes'           => $symptomsData['eyes'],
            'symptoms_digestive'      => $symptomsData['digestive'],
            'flare_up'                => $flareSuspected || $flareCount >= 3,
            'flare_suspected'         => $flareSuspected,
            'flare_answers'           => $flareAnswers,
            'notes'                   => $request->notes,
        ]);

        return redirect()->route('symptoms.index')
                         ->with('success', 'Symptômes enregistrés !');
    }

    public function show(SymptomLog $symptomLog)
    {
        if ($symptomLog->patient_id !== Auth::id() && !Auth::user()->isDoctor()) {
            abort(403);
        }
        return view('symptoms.show', compact('symptomLog'));
    }

    public function addComment(Request $request, SymptomLog $symptomLog)
    {
        if (!Auth::user()->isDoctor()) abort(403);

        $request->validate([
            'doctor_comment' => 'required|string|max:1000',
        ]);

        $symptomLog->update(['doctor_comment' => $request->doctor_comment]);

        return back()->with('success', 'Commentaire ajouté.');
    }
}