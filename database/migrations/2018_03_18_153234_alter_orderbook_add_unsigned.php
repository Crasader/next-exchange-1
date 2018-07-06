<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderbookAddUnsigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->decimal('order_amount', 20, 9)
                ->unsigned()
                ->change();
            $table->decimal('executed_amount', 20, 9)
                ->unsigned()
                ->change();
            $table->decimal('order_fee', 20, 9)
                ->unsigned()
                ->change();
            $table->decimal('order_cost', 20, 9)
                ->unsigned()
                ->change();
            $table->decimal('order_price', 20, 9)
                ->unsigned()
                ->change();
            $table->decimal('order_total', 20, 9)
                ->unsigned()
                ->change();
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

            $table->decimal('order_amount', 20, 9)
                ->unsigned(false)
                ->change();
            $table->decimal('executed_amount', 20, 9)
                ->unsigned(false)
                ->change();
            $table->decimal('order_fee', 20, 9)
                ->unsigned(false)
                ->change();
            $table->decimal('order_cost', 20, 9)
                ->unsigned(false)
                ->change();
            $table->decimal('order_price', 20, 9)
                ->unsigned(false)
                ->change();
            $table->decimal('order_total', 20, 9)
                ->unsigned(false)
                ->change();
        });
    }
}
