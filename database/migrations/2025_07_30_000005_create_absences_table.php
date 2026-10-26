<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsencesTable extends Migration
{
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medecin_id')->constrained('medecins')->cascadeOnDelete();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('motif', ['maladie', 'conges', 'autre']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absences');
    }
}
