<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('costumer_id');

            $table->string("image")->nullable();
            
            $table->foreign('costumer_id')
              ->references('id')->on('costumers')
              ->onDelete('cascade');

            $table->timestamps();
        });
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('costumer_id');

            $table->string("video")->nullable();
            
            $table->foreign('costumer_id')
              ->references('id')->on('costumers')
              ->onDelete('cascade');

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
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('videos');
    }
}
