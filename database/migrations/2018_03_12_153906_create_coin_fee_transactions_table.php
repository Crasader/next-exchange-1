<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinFeeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_fee_transactions', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('from_user_id');
            $table->unsignedInteger('transaction_id')
                ->comment('ID from transaction table');
            
            $table->string('transaction_rxid',128)
                ->nullable()
                ->comment('ID from blockchain or other external places');
            
            $table->decimal('transaction_amount',24,18)
                ->comment('Total amount of coins');
            
            $table->decimal('transaction_fee',24, 18)->default(0);
            $table->decimal('gas_fee',24,18)
                ->default(0)
                ->comment('Amount deduced as gas fee from transaction fee, Actual fee from customer is the sum of transaction_fee and gas_fee');
            
            $table->string('transaction_ip',25);
            $table->tinyInteger('transaction_type')
                ->comment('1 => Withdraw, 2 => Transfer(Order Execution also in transfer)');
            
            $table->unsignedInteger('transaction_maincoin');
            $table->unsignedInteger('coin_fee_wallet_id')->default(0);
            $table->decimal('coin_fee_wallet_balance', 32, 18)->default(0);
            $table->integer('transaction_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_fee_transactions');
    }
}
