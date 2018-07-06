<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->unsignedInteger('order_user_id');
            $table->string('order_amount',25);
            $table->string('order_market',25);
            $table->float('order_fee',20, 18);
            $table->float('order_cost',20,18);
            $table->string('order_ip',25);
            $table->float('order_price',20,18);
            $table->tinyInteger('order_buysell');
            $table->tinyInteger('order_executed');
            $table->string('order_maincoin',10);
            $table->integer('order_coin_id');

            $table->timestamps();

            $table->foreign('order_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
