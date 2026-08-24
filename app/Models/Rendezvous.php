<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rendezvous extends Model
{
    protected $table = 'rendezvous';

    protected $fillable = [
        'patient_id', 'doctor_id', 'scheduled_at', 'status', 'reason', 'doctor_notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
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
}