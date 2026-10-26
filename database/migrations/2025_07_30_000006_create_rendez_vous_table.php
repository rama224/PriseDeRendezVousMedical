<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRendezVousTable extends Migration
{
    public function up()
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('medecin_id')->constrained('medecins')->cascadeOnDelete();
            $table->dateTime('date_heure');
            $table->string('motif')->nullable();
            $table->enum('statut', ['confirme', 'annule'])->default('confirme');
            $table->timestamps();

            $table->index(['medecin_id', 'date_heure']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
}
