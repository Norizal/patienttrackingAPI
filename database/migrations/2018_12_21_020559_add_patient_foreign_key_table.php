<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPatientForeignKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient', function (Blueprint $table) {
             $table->foreign('hukm_id')->references('hukm_id')->on('patient_hukm')->on('patient_hukm')->onDelete('cascade');
            $table->foreign('beacon_id')->references('beacon_id')->on('beacon')->on('beacon')->onDelete('cascade');
            $table->foreign('medical_status_id')->references('medical_status_id')->on('medical_status')->on('medical_status')->onDelete('cascade');
            $table->foreign('location_id')->references('location_id')->on('location')->on('location')->onDelete('cascade');
            $table->foreign('kin_id')->references('kin_id')->on('kin')->on('kin')->onDelete('cascade');
            $table->foreign('ppukm_id')->references('ppukm_id')->on('ppukm')->on('ppukm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient', function (Blueprint $table) {
            //
        });
    }
}
