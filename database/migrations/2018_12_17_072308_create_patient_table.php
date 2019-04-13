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
            $table->increments('patient_id');
            $table->integer('hukm_id')->unsigned();
            $table->string('device_mac')->unique();
            $table->string('gateway_mac')->nullable()->unique();
            $table->integer('medical_status_id')->unsigned();
            $table->integer('kin_id')->unsigned()->nullable();
            $table->string('barcode')->unique();
            $table->integer('ppukm_id')->unsigned();
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
        Schema::dropIfExists('patient');
    }
}
