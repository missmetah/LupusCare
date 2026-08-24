<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disponibilites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            // Jour de la semaine : 1=Lundi, 2=Mardi ... 7=Dimanche
            $table->unsignedTinyInteger('jour_semaine');
            $table->time('heure_debut');
            $table->time('heure_fin');
            // Durée d'une consultation en minutes (ex: 30)
            $table->unsignedSmallInteger('duree_consultation')->default(30);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disponibilites');
    }
};