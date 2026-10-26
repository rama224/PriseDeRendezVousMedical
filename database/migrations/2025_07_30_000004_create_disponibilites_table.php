<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisponibilitesTable extends Migration
{
    /**
     * Plages horaires hebdomadaires de consultation d'un médecin
     * (jour_semaine au format ISO : 1 = lundi ... 7 = dimanche).
     */
    public function up()
    {
        Schema::create('disponibilites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('medecins')->cascadeOnDelete();
            $table->unsignedTinyInteger('jour_semaine');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disponibilites');
    }
}
