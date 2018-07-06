<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionsAddFeeCollectionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->decimal('gas_fee_amount', 20,9)
                ->nullable()
                ->after('transaction_fee');

            $table->tinyInteger('is_fees')
                ->default(0)
                ->after('transaction_amount')
                ->comment('1 => transaction amount collected as fees');

            $table->integer('order_id')
                ->comment('if is_fees is 1 this field represents fees collected for which order')
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
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropColumn([
                'gas_fee_amount', 'is_fees'
            ]);


            $table->integer('order_id')
                ->comment('')
                ->change();
        });
    }
}
