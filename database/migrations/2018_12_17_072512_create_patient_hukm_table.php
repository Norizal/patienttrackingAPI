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
            $table->increments('hukm_id');
            $table->string('hukm_icnumber')->unique();
            $table->string('hukm_name');
            $table->string('hukm_mrn')->unique();
            $table->string('hukm_gender');
            $table->string('hukm_age');
            $table->string('hukm_race');
            $table->string('hukm_phonenumber');
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
