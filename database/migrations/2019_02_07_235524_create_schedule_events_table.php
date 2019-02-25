<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');

            $table->string("eventType");
            $table->string("name");
            $table->string('email')->unique();
            $table->timestamp('date');
            $table->string("location");
            
            $table->text("address");
            $table->text("description");
            $table->string('phone');
            
            // $table->foreign('user_id')
            //   ->references('id')->on('users')
            //   ->onDelete('cascade');

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
        Schema::dropIfExists('schedule_events');
    }
}