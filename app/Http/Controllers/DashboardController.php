<?php

namespace App\Http\Controllers;

use App\Models\Rendezvous;
use App\Models\SymptomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
         if ($user->isAdmin()) {
        return redirect()->route('admin.index');
    }
        if ($user->isDoctor()) {
            $data = [
                'totalPatients'     => $user->rendezvousAsDoctor()
                                            ->distinct('patient_id')->count(),
                'rendezvousMonth'   => $user->rendezvousAsDoctor()
                                            ->whereMonth('scheduled_at', now()->month)->count(),
                'pendingRendezvous' => $user->rendezvousAsDoctor()
                                            ->where('status', 'pending')->count(),
                'nextRendezvous'    => $user->rendezvousAsDoctor()
                                            ->where('scheduled_at', '>=', now())
                                            ->where('status', 'confirmed')
                                            ->orderBy('scheduled_at')
                                            ->with('patient')
                                            ->first(),
                'recentRendezvous'  => $user->rendezvousAsDoctor()
                                            ->with('patient')
                                            ->orderBy('scheduled_at')
                                            ->take(5)
                                            ->get(),
                'chartData'         => collect(),
            ];
        } else {
            $data = [
                'nextRendezvous'    => $user->rendezvousAsPatient()
                                            ->where('scheduled_at', '>=', now())
                                            ->where('status', 'confirmed')
                                            ->orderBy('scheduled_at')
                                            ->with('doctor')
                                            ->first(),
                'rendezvousMonth'   => $user->rendezvousAsPatient()
                                            ->whereMonth('scheduled_at', now()->month)->count(),
                'pendingRendezvous' => $user->rendezvousAsPatient()
                                            ->where('status', 'pending')->count(),
                'avgPain'           => $user->symptomLogs()
                                            ->whereBetween('logged_at', [now()->subDays(7), now()])
                                            ->avg('pain_level'),
                'flaresMonth'       => $user->symptomLogs()
                                            ->whereMonth('logged_at', now()->month)
                                            ->where('flare_up', true)->count(),
                'recentRendezvous'  => $user->rendezvousAsPatient()
                                            ->with('doctor')
                                            ->orderBy('scheduled_at')
                                            ->take(5)
                                            ->get(),
                'recentSymptoms'    => $user->symptomLogs()
                                            ->orderBy('logged_at', 'desc')
                                            ->take(4)
                                            ->get(),
                'chartData'         => $user->symptomLogs()
                                            ->orderBy('logged_at')
                                            ->take(14)
                                            ->get(['logged_at', 'pain_level', 'fatigue_level', 'sleep_quality']),
            ];
        }

        return view('dashboard', $data);
    }
}