<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    protected $fillable = [
        'doctor_id',
        'date_disponible',
        'heure_debut',
        'heure_fin',
        'duree_consultation',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'actif'            => 'boolean',
            'date_disponible'  => 'date',
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Génère tous les créneaux horaires
    public function genererCreneaux(): array
    {
        $creneaux = [];
        $debut = Carbon::createFromTimeString($this->heure_debut);
        $fin   = Carbon::createFromTimeString($this->heure_fin);

        while ($debut->copy()->addMinutes($this->duree_consultation)->lte($fin)) {
            $creneaux[] = $debut->format('H:i');
            $debut->addMinutes($this->duree_consultation);
        }

        return $creneaux;
    }

    // Créneaux libres pour cette disponibilité
    public function creneauxLibres(int $doctorId): array
    {
        $creneaux = $this->genererCreneaux();
        $pris = Rendezvous::where('doctor_id', $doctorId)
                          ->whereDate('scheduled_at', $this->date_disponible)
                          ->where('status', '!=', 'refused')
                          ->where('status', '!=', 'cancelled')
                          ->pluck('scheduled_at')
                          ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
                          ->toArray();

        return array_values(array_filter($creneaux, fn($c) => !in_array($c, $pris)));
    }
}