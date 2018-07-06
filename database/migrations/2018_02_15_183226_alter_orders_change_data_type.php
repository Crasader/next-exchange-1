<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersChangeDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('order_fee')->change();
            $table->string('order_cost')->change();
            $table->string('order_price')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('orders', function (Blueprint $table) {

            $table->float('order_fee',20, 18)->change();
            $table->float('order_cost',20,18)->change();
            $table->float('order_price',20,18)->change();
        });
    }
}
