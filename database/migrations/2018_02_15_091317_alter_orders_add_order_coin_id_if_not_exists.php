<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersAddOrderCoinIdIfNotExists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasColumn('orders', 'order_coin_id'))  //check whether users table has email column
        {
            Schema::table('orders', function (Blueprint $table) {

                $table->integer('order_coin_id')
                    ->after('order_maincoin');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn('order_coin_id');
        });
    }
}
