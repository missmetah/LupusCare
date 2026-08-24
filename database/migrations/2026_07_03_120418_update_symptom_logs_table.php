<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('symptom_logs', function (Blueprint $table) {
            // Symptômes prédéfinis en JSON
            $table->json('symptoms')->nullable()->after('patient_id');
            // Échelles
            $table->unsignedTinyInteger('pain_level')->nullable()->change();
            $table->unsignedTinyInteger('fatigue_level')->nullable()->change();
            $table->unsignedTinyInteger('sleep_quality')->nullable()->after('fatigue_level');
            // Notes et poussée
            $table->boolean('flare_up')->default(false)->change();
            $table->text('notes')->nullable()->after('flare_up');
            // Commentaire médecin
            $table->text('doctor_comment')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('symptom_logs', function (Blueprint $table) {
            $table->dropColumn(['symptoms', 'sleep_quality', 'notes', 'doctor_comment']);
        });
    }
};