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
            $table->increments('id');
            $table->string('name');
            $table->string('staffid')->unique();
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('race');
            $table->string('phonenumber');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
