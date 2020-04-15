<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            // $table->string('email')->unique();
            $table->boolean('confirmed')->default($value = 0);

            $table->string('name');
            $table->string('title');
            $table->text('body');
            $table->dateTime('date');

            $table->softDeletes();

            $table->timestamps();

            // $table->foreign('user_id')
            //   ->references('id')->on('articles')
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
        Schema::dropIfExists('articles');
    }
}
