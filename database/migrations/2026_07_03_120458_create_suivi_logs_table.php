<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rendezvous_id')->nullable()->constrained('rendezvous')->nullOnDelete();
            $table->date('consultation_date');
            $table->unsignedTinyInteger('disease_activity')->nullable(); // 1-5
            $table->text('consultation_summary')->nullable();
            $table->text('treatment')->nullable();
            $table->text('next_steps')->nullable();
            $table->text('symptom_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivi_logs');
    }
};