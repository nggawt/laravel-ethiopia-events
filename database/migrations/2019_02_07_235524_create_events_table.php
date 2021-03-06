<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->boolean('confirmed')->default($value = 0);

            $table->string("eventType");
            $table->string("name");
            $table->string('email')->unique();
            $table->dateTime('date');
            $table->string("location");
            
            $table->text("address");
            $table->text("descritions");
            $table->string('phone');

            $table->softDeletes();

            $table->timestamps();
            
            // $table->foreign('user_id')
            //   ->references('id')->on('users')
            //   ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
