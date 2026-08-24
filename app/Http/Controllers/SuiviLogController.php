<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuiviLog;
use App\Models\Rendezvous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuiviLogController extends Controller
{
    // Liste des notes de suivi d'un patient (médecin + patient)
    public function index(User $patient)
    {
        $user = Auth::user();

        if (!$user->isDoctor() && $user->id !== $patient->id) {
            abort(403);
        }

        $suivis = SuiviLog::where('patient_id', $patient->id)
                          ->with(['doctor', 'rendezvous'])
                          ->orderBy('consultation_date', 'desc')
                          ->paginate(10);

        return view('suivi.index', compact('suivis', 'patient'));
    }

    // Formulaire de création (médecin uniquement)
    public function create(User $patient)
    {
        if (!Auth::user()->isDoctor()) abort(403);

        $rendezvous = Rendezvous::where('patient_id', $patient->id)
                                ->where('doctor_id', Auth::id())
                                ->where('status', 'confirmed')
                                ->orderBy('scheduled_at', 'desc')
                                ->get();

        return view('suivi.create', compact('patient', 'rendezvous'));
    }

    // Enregistrement
    public function store(Request $request, User $patient)
    {
        if (!Auth::user()->isDoctor()) abort(403);

        $request->validate([
            'consultation_date'    => 'required|date',
            'disease_activity'     => 'required|integer|min:1|max:5',
            'consultation_summary' => 'nullable|string|max:2000',
            'treatment'            => 'nullable|string|max:1000',
            'next_steps'           => 'nullable|string|max:1000',
            'symptom_comment'      => 'nullable|string|max:1000',
            'rendezvous_id'        => 'nullable|exists:rendezvous,id',
        ]);

        SuiviLog::create([
            'patient_id'           => $patient->id,
            'doctor_id'            => Auth::id(),
            'rendezvous_id'        => $request->rendezvous_id,
            'consultation_date'    => $request->consultation_date,
            'disease_activity'     => $request->disease_activity,
            'consultation_summary' => $request->consultation_summary,
            'treatment'            => $request->treatment,
            'next_steps'           => $request->next_steps,
            'symptom_comment'      => $request->symptom_comment,
        ]);

        return redirect()->route('suivi.index', $patient)
                         ->with('success', 'Note de suivi enregistrée !');
    }
}