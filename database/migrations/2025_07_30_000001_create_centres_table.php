<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentresTable extends Migration
{
    public function up()
    {
        Schema::create('centres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('telephone', 30);
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('centres');
    }
}
