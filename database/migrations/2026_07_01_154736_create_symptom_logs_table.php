<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('symptom_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->date('logged_at');
            $table->unsignedTinyInteger('pain_level')->nullable();
            $table->unsignedTinyInteger('fatigue_level')->nullable();
            $table->boolean('flare_up')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('symptom_logs');
    }
};