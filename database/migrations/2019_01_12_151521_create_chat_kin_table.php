<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatKinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_kin', function (Blueprint $table) {
            $table->increments('chat_kin_id');
            $table->string('chat_kin_message');
            $table->integer('chat_kin_status_sent')->default('0');
            $table->integer('chat_kin_status_read')->default('0');
            $table->integer('patient_id')->unsigned();
            $table->timestamps();

            $table->foreign('patient_id')->references('patient_id')->on('patient')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_kin');
    }
}
