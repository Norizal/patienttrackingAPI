<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientHukmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_hukm', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icnumber')->unique();
            $table->string('name');
            $table->string('mrn')->unique();
            $table->string('gender');
            $table->string('age');
            $table->string('race');
            $table->string('phonenumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_hukm');
    }
}
