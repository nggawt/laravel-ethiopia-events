<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('email_from')->nullable();
            $table->string('email_to')->nullable();
            $table->string('name');
            $table->string('subject');
            $table->text('body');
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('date')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
              ->references('id')->on('messages')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
