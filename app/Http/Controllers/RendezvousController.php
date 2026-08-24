<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rendezvous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RendezvousNotification;

class RendezvousController extends Controller
{
    // Liste des rendez-vous
    public function index()
    {
        $user = Auth::user();

        if ($user->isDoctor()) {
            $rendezvous = $user->rendezvousAsDoctor()
                               ->with('patient')
                               ->orderBy('scheduled_at', 'desc')
                               ->get();
        } else {
            $rendezvous = $user->rendezvousAsPatient()
                               ->with('doctor')
                               ->orderBy('scheduled_at', 'desc')
                               ->get();
        }

        return view('rendezvous.index', compact('rendezvous'));
    }

    // Formulaire de création
    public function create()
    {
        $user = Auth::user();

        if ($user->isDoctor()) {
            $patients = User::where('role', 'patient')->get();
            return view('rendezvous.create', compact('patients'));
        } else {
            $doctors = User::where('role', 'doctor')->get();
            return view('rendezvous.create', compact('doctors'));
        }
    }

    // Enregistrement
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isDoctor()) {
            $request->validate([
                'patient_id'   => 'required|exists:users,id',
                'scheduled_at' => 'required|date|after:now',
                'reason'       => 'nullable|string|max:500',
            ]);

            $rdv = Rendezvous::create([
                'doctor_id'    => Auth::id(),
                'patient_id'   => $request->patient_id,
                'scheduled_at' => $request->scheduled_at,
                'reason'       => $request->reason,
                'status'       => 'confirmed',
            ]);

            // Notifier le patient
            $rdv->patient->notify(new RendezvousNotification($rdv, 'confirmed'));

        } else {
            $request->validate([
                'doctor_id'    => 'required|exists:users,id',
                'scheduled_at' => 'required|date|after:now',
                'reason'       => 'nullable|string|max:500',
            ]);

            $rdv = Rendezvous::create([
                'patient_id'   => Auth::id(),
                'doctor_id'    => $request->doctor_id,
                'scheduled_at' => $request->scheduled_at,
                'reason'       => $request->reason,
                'status'       => 'pending',
            ]);

            // Notifier le médecin
            $rdv->doctor->notify(new RendezvousNotification($rdv, 'created'));
        }

        return redirect()->route('rendezvous.index')
                         ->with('success', 'Rendez-vous créé avec succès !');
    }

    // Confirmer ou refuser (médecin)
    public function updateStatus(Request $request, Rendezvous $rendezvous)
    {
        $request->validate([
            'status' => 'required|in:confirmed,refused,completed,cancelled',
        ]);

        $rendezvous->update(['status' => $request->status]);

        // Notifier le patient
        $rendezvous->patient->notify(
            new RendezvousNotification($rendezvous, $request->status)
        );

        return back()->with('success', 'Statut mis à jour.');
    }
}