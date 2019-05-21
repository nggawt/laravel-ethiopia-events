<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            // $table->string('email')->unique();
            $table->boolean('confirmed')->default($value = 0);

            $table->string('name');
            $table->string('title');
            $table->text('body');
            $table->dateTime('date');
            $table->string('slug')->unique()->nullable();

            $table->timestamps();

            // $table->foreign('user_id')
            //   ->references('id')->on('posts')
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
        Schema::dropIfExists('posts');
    }
}
