<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disponibilites', function (Blueprint $table) {
          
            $table->date('date_disponible')->nullable()->after('doctor_id');
            $table->dropColumn('jour_semaine');
        });
    }

    public function down(): void
    {
        Schema::table('disponibilites', function (Blueprint $table) {
            $table->dropColumn('date_disponible');
            $table->unsignedTinyInteger('jour_semaine')->nullable()->after('doctor_id');
        });
    }
};