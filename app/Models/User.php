<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // --- Rôles ---

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // --- Statuts ---

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRefused(): bool
    {
        return $this->status === 'refused';
    }

    // --- Profils ---

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    // --- Rendez-vous ---

    public function rendezvousAsPatient()
    {
        return $this->hasMany(Rendezvous::class, 'patient_id');
    }

    public function rendezvousAsDoctor()
    {
        return $this->hasMany(Rendezvous::class, 'doctor_id');
    }

    // --- Symptômes ---

    public function symptomLogs()
    {
        return $this->hasMany(SymptomLog::class, 'patient_id');
    }
}