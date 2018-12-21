<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hukm_id')->unsigned();
            $table->integer('beacon_id')->unsigned();
            $table->integer('medical_status_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('kin_id')->unsigned();
            $table->string('barcode')->unique();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            // $table->foreign('hukm_id')->references('id')->on('patient_hukm');
            // $table->foreign('beacon_id')->references('id')->on('beacon');
            // $table->foreign('medical_status_id')->references('id')->on('medical_status');
            // $table->foreign('location_id')->references('id')->on('location');
            // $table->foreign('kin_id')->references('id')->on('kin');
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient');
    }
}
