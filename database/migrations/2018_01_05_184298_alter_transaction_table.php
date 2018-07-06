<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionTable extends Migration
{
    /**
     * Transaction type:
     *
     * (int)0 = internal
     * (int)1 = incoming (deposit)
     * (int)2 = outgoing (withdraw)
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->tinyInteger('transaction_type')
                ->default(0)
                ->after('transaction_status');
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

            $table->dropColumn('transaction_type');
        });
    }
}
