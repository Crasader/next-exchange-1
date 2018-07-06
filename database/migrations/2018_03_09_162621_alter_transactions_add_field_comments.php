<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionsAddFieldComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->string('transaction_txid',128)->comment('Internal transaction ID')->change();
            $table->string('transaction_rxid',128)->comment('Transaction ID from external source like Paypal..')->change();
            $table->integer('transaction_market')->default(0)->change();
            $table->smallInteger('transaction_buysell')->tinyInteger('transaction_buysell')->default(0)->comment('1 =>Buy, 2 =>Sell')->change();
            $table->smallInteger('transaction_type')->tinyInteger('transaction_type')->default(0)->comment('1 =>Deposit, 2 =>Withdraw, 3 =>Transfer')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('transactions', function (Blueprint $table) {

            $table->string('transaction_txid',128)->comment('')->change();
            $table->string('transaction_rxid',128)->comment('')->change();
            $table->smallInteger('transaction_buysell')->tinyInteger('transaction_buysell')->comment('')->change();
            $table->smallInteger('transaction_type')->tinyInteger('transaction_type')->comment('')->change();
        });
    }
}
