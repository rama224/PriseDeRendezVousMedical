<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialitesTable extends Migration
{
    public function up()
    {
        Schema::create('specialites', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('specialites');
    }
}
