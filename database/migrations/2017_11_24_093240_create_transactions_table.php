<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('transaction_id');
            $table->unsignedInteger('transaction_user_id');
            $table->string('transaction_txid',128)->unique();  // TXid
            $table->string('transaction_rxid',128)->nulled();
            $table->string('transaction_addr',128)->nulled();  // BTC address
            $table->float('transaction_amount',24,18); // BTC
            $table->unsignedInteger('transaction_market'); // BTC
            $table->float('transaction_fee',24, 18)->default(0);
            $table->float('transaction_cost',24,18)->default(0);
            $table->string('transaction_ip',25);
            $table->float('transaction_price',32,18); // PRICE 1 NEXT
            $table->tinyInteger('transaction_buysell'); // BUY
            $table->float('transaction_maincoin_amount',32, 18); // NEXT amount
            $table->unsignedInteger('transaction_maincoin'); // NEXT
            $table->unsignedInteger('transaction_maincoin_wallet_id')->nulled();
            $table->float('transaction_maincoin_wallet_balance', 32, 18)->nulled();
            $table->integer('transaction_confirmations')->default(0); //
            $table->integer('transaction_status')->default(0);
 
            $table->timestamps();

            $table->foreign('transaction_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('transaction_maincoin')->references('coin_id')->on('coins')->onDelete('cascade');
            $table->foreign('transaction_maincoin_wallet_id')->references('id')->on('wallet')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
