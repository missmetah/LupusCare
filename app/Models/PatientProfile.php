<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    protected $fillable = [
        'user_id', 'doctor_id', 'birth_date', 'sex', 'diagnosis_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'diagnosis_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}