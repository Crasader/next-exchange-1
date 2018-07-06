<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->increments('trade_id');
            $table->unsignedInteger('trade_user_id');
            $table->string('trade_amount',25);
            $table->string('trade_market',25);
            $table->float('trade_fee',20, 18);
            $table->float('trade_cost',20,18);
            $table->string('trade_ip',25);
            $table->float('trade_price',20,18);
            $table->tinyInteger('trade_buysell');
            $table->string('trade_maincoin',10);
            $table->integer('trade_charttime');
 
            $table->timestamps();

            $table->foreign('trade_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trades');
    }
}
