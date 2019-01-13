<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpukmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppukm', function (Blueprint $table) {
            $table->increments('ppukm_id');
            $table->string('ppukm_name');
            $table->string('ppukm_staffid')->unique();
            $table->string('ppukm_email')->unique();
            $table->string('ppukm_phonenumber');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

           

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ppukm');
    }
}
