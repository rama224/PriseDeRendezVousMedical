<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedecinsTable extends Migration
{
    public function up()
    {
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->foreignId('specialite_id')->constrained('specialites')->restrictOnDelete();
            $table->foreignId('centre_id')->constrained('centres')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medecins');
    }
}
