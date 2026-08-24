<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
   protected $fillable = [
        'user_id',
        'specialty',
        'phone',
        'clinic_name',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
