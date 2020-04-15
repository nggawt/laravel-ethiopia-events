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
            $table->boolean('confirmed')->default($value = 0);
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
