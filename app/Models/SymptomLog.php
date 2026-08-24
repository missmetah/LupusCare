<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SymptomLog extends Model
{
    protected $fillable = [
        'patient_id',
        'logged_at',
        'pain_level',
        'fatigue_level',
        'sleep_quality',
        'frequency',
        'symptoms_general',
        'symptoms_pain',
        'symptoms_skin',
        'symptoms_kidney',
        'symptoms_respiratory',
        'symptoms_cardiovascular',
        'symptoms_neurological',
        'symptoms_eyes',
        'symptoms_digestive',
        'flare_up',
        'flare_suspected',
        'flare_answers',
        'notes',
        'doctor_comment',
    ];

    protected function casts(): array
    {
        return [
            'logged_at'              => 'date',
            'flare_up'               => 'boolean',
            'flare_suspected'        => 'boolean',
            'symptoms_general'       => 'array',
            'symptoms_pain'          => 'array',
            'symptoms_skin'          => 'array',
            'symptoms_kidney'        => 'array',
            'symptoms_respiratory'   => 'array',
            'symptoms_cardiovascular'=> 'array',
            'symptoms_neurological'  => 'array',
            'symptoms_eyes'          => 'array',
            'symptoms_digestive'     => 'array',
            'flare_answers'          => 'array',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Retourne toutes les catégories avec leurs symptômes
    public function allSymptoms(): array
    {
        return array_filter([
            'Général'          => $this->symptoms_general,
            'Douleurs'         => $this->symptoms_pain,
            'Peau'             => $this->symptoms_skin,
            'Reins'            => $this->symptoms_kidney,
            'Respiratoire'     => $this->symptoms_respiratory,
            'Cardiovasculaire' => $this->symptoms_cardiovascular,
            'Neurologique'     => $this->symptoms_neurological,
            'Yeux'             => $this->symptoms_eyes,
            'Digestif'         => $this->symptoms_digestive,
        ]);
    }

    public function severeSymptoms(): array
    {
        $severe = [];
        foreach ($this->allSymptoms() as $category => $symptoms) {
            foreach ($symptoms as $symptom => $intensity) {
                if ($intensity === 'sévère') {
                    $severe[] = $symptom;
                }
            }
        }
        return $severe;
    }
}