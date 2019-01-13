<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatPpukmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_ppukm', function (Blueprint $table) {
            $table->increments('chat_ppukm_id');
            $table->string('chat_ppukm_message');
            $table->integer('chat_ppukm_status_sent')->default('0');
            $table->integer('chat_ppukm_status_read')->default('0');
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
        Schema::dropIfExists('chat_ppukm');
    }
}
