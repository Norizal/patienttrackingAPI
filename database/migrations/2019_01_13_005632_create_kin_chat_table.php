<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKinChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kin_chat', function (Blueprint $table) {
            $table->increments('kin_chat_id');
            $table->string('kin_chat_message');
            $table->integer('kin_chat_status_sent')->default('0');
            $table->integer('kin_chat_status_read')->default('0');
            $table->integer('kin_id')->unsigned();
            $table->integer('patient_id')->unsigned();
            $table->timestamps();

            $table->foreign('kin_id')->references('kin_id')->on('kin')->onDelete('cascade');
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
        Schema::dropIfExists('kin_chat');
    }
}
