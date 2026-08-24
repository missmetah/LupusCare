<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\DoctorStatusNotification;

class AdminController extends Controller
{
    // Vérifie que l'utilisateur est admin
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) abort(403);
    }

    // Dashboard admin
    public function index()
    {
        $this->checkAdmin();

        $data = [
            'pendingDoctors' => User::where('role', 'doctor')
                                    ->where('status', 'pending')
                                    ->get(),
            'activeDoctors'  => User::where('role', 'doctor')
                                    ->where('status', 'active')
                                    ->count(),
            'totalPatients'  => User::where('role', 'patient')->count(),
            'refusedDoctors' => User::where('role', 'doctor')
                                    ->where('status', 'refused')
                                    ->count(),
        ];

        return view('admin.index', $data);
    }

    // Valider ou refuser un médecin
    public function updateDoctorStatus(Request $request, User $user)
    {
        $this->checkAdmin();

        $request->validate([
            'status' => 'required|in:active,refused',
        ]);

        $user->update(['status' => $request->status]);

        // Notifier le médecin
        $user->notify(new DoctorStatusNotification($request->status));

        $message = $request->status === 'active'
            ? 'Médecin validé avec succès.'
            : 'Médecin refusé.';

        return back()->with('success', $message);
    }

    // Liste de tous les médecins
    public function doctors()
    {
        $this->checkAdmin();

        $doctors = User::where('role', 'doctor')
                       ->with('doctorProfile')
                       ->orderBy('status')
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('admin.doctors', compact('doctors'));
    }

    // Liste de tous les patients
    public function patients()
    {
        $this->checkAdmin();

        $patients = User::where('role', 'patient')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.patients', compact('patients'));
    }
}