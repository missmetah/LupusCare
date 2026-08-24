<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disponibilite;
use Illuminate\Support\Facades\Auth;

class DisponibiliteController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isDoctor()) abort(403);

        $disponibilites = Disponibilite::where('doctor_id', Auth::id())
                                       ->where('date_disponible', '>=', today())
                                       ->orderBy('date_disponible')
                                       ->orderBy('heure_debut')
                                       ->get();

        return view('disponibilites.index', compact('disponibilites'));
    }

    public function create()
    {
        if (!Auth::user()->isDoctor()) abort(403);
        return view('disponibilites.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isDoctor()) abort(403);

        $request->validate([
            'date_disponible'    => 'required|date|after:today',
            'heure_debut'        => 'required',
            'heure_fin'          => 'required|after:heure_debut',
            'duree_consultation' => 'required|integer|min:15|max:120',
        ]);

        // Vérifier chevauchement sur la même date
        $chevauchement = Disponibilite::where('doctor_id', Auth::id())
            ->where('date_disponible', $request->date_disponible)
            ->where('actif', true)
            ->where(function ($q) use ($request) {
                $q->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
                  ->orWhereBetween('heure_fin', [$request->heure_debut, $request->heure_fin]);
            })->exists();

        if ($chevauchement) {
            return back()->withErrors(['heure_debut' => 'Ce créneau chevauche une disponibilité existante pour cette date.']);
        }

        Disponibilite::create([
            'doctor_id'          => Auth::id(),
            'date_disponible'    => $request->date_disponible,
            'heure_debut'        => $request->heure_debut,
            'heure_fin'          => $request->heure_fin,
            'duree_consultation' => $request->duree_consultation,
            'actif'              => true,
        ]);

        return redirect()->route('disponibilites.index')
                         ->with('success', 'Disponibilité ajoutée !');
    }

    public function destroy(Disponibilite $disponibilite)
    {
        if ($disponibilite->doctor_id !== Auth::id()) abort(403);
        $disponibilite->delete();
        return back()->with('success', 'Disponibilité supprimée.');
    }

    // API : dates disponibles pour un médecin
    public function joursDispo(Request $request)
    {
        $request->validate(['doctor_id' => 'required|exists:users,id']);

        $dates = Disponibilite::where('doctor_id', $request->doctor_id)
                              ->where('actif', true)
                              ->where('date_disponible', '>=', today())
                              ->pluck('date_disponible')
                              ->map(fn($d) => $d->format('Y-m-d'))
                              ->unique()
                              ->values()
                              ->toArray();

        return response()->json($dates);
    }

    // API : créneaux libres pour une date et un médecin
    public function creneaux(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date'      => 'required|date',
        ]);

        $disponibilites = Disponibilite::where('doctor_id', $request->doctor_id)
                                       ->where('date_disponible', $request->date)
                                       ->where('actif', true)
                                       ->get();

        $creneaux = [];
        foreach ($disponibilites as $dispo) {
            foreach ($dispo->creneauxLibres($request->doctor_id) as $creneau) {
                $creneaux[] = $creneau;
            }
        }

        sort($creneaux);
        return response()->json(array_values(array_unique($creneaux)));
    }
}