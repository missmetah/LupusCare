<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('symptom_logs', function (Blueprint $table) {
            // Symptômes par catégorie avec intensité (JSON)
            // Structure : { "fatigue": "modéré", "fièvre": "léger", ... }
            $table->json('symptoms_general')->nullable();
            $table->json('symptoms_pain')->nullable();
            $table->json('symptoms_skin')->nullable();
            $table->json('symptoms_kidney')->nullable();
            $table->json('symptoms_respiratory')->nullable();
            $table->json('symptoms_cardiovascular')->nullable();
            $table->json('symptoms_neurological')->nullable();
            $table->json('symptoms_eyes')->nullable();
            $table->json('symptoms_digestive')->nullable();

            // Fréquence globale
            $table->string('frequency')->nullable(); // aujourd'hui | plusieurs_jours | occasionnel | permanent

            // Poussée détaillée
            $table->boolean('flare_suspected')->default(false);
            $table->json('flare_answers')->nullable(); // réponses aux 9 questions
        });
    }

    public function down(): void
    {
        Schema::table('symptom_logs', function (Blueprint $table) {
            $table->dropColumn([
                'symptoms_general', 'symptoms_pain', 'symptoms_skin',
                'symptoms_kidney', 'symptoms_respiratory', 'symptoms_cardiovascular',
                'symptoms_neurological', 'symptoms_eyes', 'symptoms_digestive',
                'frequency', 'flare_suspected', 'flare_answers',
            ]);
        });
    }
};