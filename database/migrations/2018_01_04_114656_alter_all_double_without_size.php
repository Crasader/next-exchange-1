<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AlterAllDoubleWithoutSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Access Table*/
        DB::unprepared('ALTER TABLE `access` MODIFY `coin_amount` DOUBLE');

        /*Orders Table*/
        DB::unprepared('ALTER TABLE `orders` MODIFY `order_fee` DOUBLE, MODIFY `order_cost` DOUBLE, MODIFY `order_price` DOUBLE');

        /*Trades Table*/
        DB::unprepared('ALTER TABLE `trades` MODIFY `trade_fee` DOUBLE, MODIFY `trade_cost` DOUBLE, MODIFY `trade_price` DOUBLE');

        /*Transactions Table*/
        DB::unprepared('ALTER TABLE `transactions` MODIFY `transaction_fee` DOUBLE, MODIFY `transaction_cost` DOUBLE, MODIFY `transaction_price` DOUBLE, MODIFY `transaction_maincoin_amount` DOUBLE, MODIFY `transaction_maincoin_wallet_balance` DOUBLE');

        /*Wallet Table*/
        DB::unprepared('ALTER TABLE `wallet` MODIFY `amount` DOUBLE, MODIFY `amount_inorder` DOUBLE');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Access Table*/
        DB::unprepared('ALTER TABLE `access` MODIFY `coin_amount` DOUBLE(32, 18)');

        /*Orders Table*/
        DB::unprepared('ALTER TABLE `orders` MODIFY `order_fee` DOUBLE(20, 18), MODIFY `order_cost` DOUBLE(20, 18), MODIFY `order_price` DOUBLE(20, 18)');

        /*Trades Table*/
        DB::unprepared('ALTER TABLE `trades` MODIFY `trade_fee` DOUBLE(20, 18), MODIFY `trade_cost` DOUBLE(20, 18), MODIFY `trade_price` DOUBLE(20, 18)');

        /*Transactions Table*/
        DB::unprepared('ALTER TABLE `transactions` MODIFY `transaction_fee` DOUBLE(24, 18), MODIFY `transaction_cost` DOUBLE(24, 18), MODIFY `transaction_price` DOUBLE(24, 18), MODIFY `transaction_maincoin_amount` DOUBLE(32, 18), MODIFY `transaction_maincoin_wallet_balance` DOUBLE(32, 18)');

        //Wallet Table
        DB::unprepared('ALTER TABLE `wallet` MODIFY `amount` DOUBLE(32, 18), MODIFY `amount_inorder` DOUBLE(32, 18)');
    }
}
