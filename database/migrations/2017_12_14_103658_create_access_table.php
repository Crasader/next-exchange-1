<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('country',2)->nullable();
            $table->string('etherId',128);
            $table->string('coin_type',10);
            $table->integer('coin_buy')->nullable();
            $table->integer('coin_sell')->nullable();
            $table->double('coin_amount', 32, 18)->nullable();
            $table->integer('fiat')->nullable();
            $table->integer('active')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access');
    }
}
