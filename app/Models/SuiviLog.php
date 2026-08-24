<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuiviLog extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'rendezvous_id',
        'consultation_date',
        'disease_activity',
        'consultation_summary',
        'treatment',
        'next_steps',
        'symptom_comment',
    ];

    protected function casts(): array
    {
        return [
            'consultation_date' => 'date',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function rendezvous()
    {
        return $this->belongsTo(Rendezvous::class);
    }

    public function activityLabel(): string
    {
        return match($this->disease_activity) {
            1 => 'Rémission',
            2 => 'Faible',
            3 => 'Modérée',
            4 => 'Élevée',
            5 => 'Très élevée',
            default => '—',
        };
    }

    public function activityColor(): string
    {
        return match($this->disease_activity) {
            1 => '#059669',
            2 => '#65A30D',
            3 => '#D97706',
            4 => '#DC2626',
            5 => '#7F1D1D',
            default => '#6B7280',
        };
    }
}