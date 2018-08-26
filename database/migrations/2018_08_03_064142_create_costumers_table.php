<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostumersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costumers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(1);
            $table->string('company');
            $table->string('businessType');
            $table->string('loggo');
            $table->string('title');
            $table->string('contact');
            $table->text('discription');
            $table->string('email')->unique();
            $table->string('tel');
            $table->string('address');
            $table->text('deals');
            $table->timestamps();
        });
            // $table->integer('user_id')->default(1);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costumers');
    }
}
