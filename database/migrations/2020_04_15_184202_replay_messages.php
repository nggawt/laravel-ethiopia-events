<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplayMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replay_messages', function (Blueprint $table) {
                // 'content', 'user_id', 'message_id'//'title', 
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('message_id');  
            $table->text('content');

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
        Schema::dropIfExists('replay_messages');
    }
}
