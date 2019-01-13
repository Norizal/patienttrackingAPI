<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpukmChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppukm_chat', function (Blueprint $table) {
            $table->increments('ppukm_chat_id');
            $table->string('ppukm_chat_message');
            $table->integer('ppukm_chat_status_sent')->default('0');
            $table->integer('ppukm_chat_status_read')->default('0');
            $table->integer('ppukm_id')->unsigned();
            $table->integer('patient_id')->unsigned();
            $table->timestamps();

            $table->foreign('ppukm_id')->references('ppukm_id')->on('ppukm')->onDelete('cascade');
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
        Schema::dropIfExists('ppukm_chat');
    }
}
