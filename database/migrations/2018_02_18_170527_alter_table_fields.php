<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->decimal('order_amount', 20, 9)->change();
            $table->decimal('order_fee', 20, 9)->change();
            $table->decimal('order_cost', 20, 9)->change();
            $table->decimal('order_price', 20, 9)->change();
            $table->decimal('order_total', 20, 9)->change();
        });

        Schema::table('wallet', function (Blueprint $table) {

            $table->decimal('amount', 20, 9)->change();
            $table->decimal('amount_inorder', 20, 9)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {

            $table->decimal('transaction_amount', 20, 9)->change();
            $table->decimal('transaction_fee', 20, 9)->change();
            $table->decimal('transaction_cost', 20, 9)->change();
            $table->decimal('transaction_price', 20, 9)->change();
            $table->decimal('transaction_maincoin_amount', 20, 9)->change();
            $table->decimal('transaction_maincoin_wallet_balance', 20, 9)->change();
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

            $table->float('order_amount', 20, 9)->change();
            $table->float('order_fee', 20, 9)->change();
            $table->float('order_cost', 20, 9)->change();
            $table->float('order_price', 20, 9)->change();
            $table->float('order_total', 20, 9)->change();
        });

        Schema::table('wallet', function (Blueprint $table) {

            $table->float('amount', 20, 9)->change();
            $table->float('amount_inorder', 20, 9)->change();
        });

        Schema::table('transactions', function (Blueprint $table) {

            $table->float('transaction_amount', 20, 9)->change();
            $table->float('transaction_fee', 20, 9)->change();
            $table->float('transaction_cost', 20, 9)->change();
            $table->float('transaction_price', 20, 9)->change();
            $table->float('transaction_maincoin_amount', 20, 9)->change();
            $table->float('transaction_maincoin_wallet_balance', 20, 9)->change();
        });
    }
}
