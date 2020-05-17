<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('company');
            $table->string('email')->unique();
            $table->string('businessType');
            $table->string('title');
            $table->string('contact');
            $table->text('descritions');
            $table->string('loggo');
            $table->string('address');
            $table->string('phone');
            $table->text('deals');
            $table->boolean('confirmed')->default($value = 0);
            
            $table->softDeletes();

            $table->foreign('user_id')
              ->references('id')->on('users')
              ->onDelete('cascade');

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
        Schema::dropIfExists('customers');
    }
}
