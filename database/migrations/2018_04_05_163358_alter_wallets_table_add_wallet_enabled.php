<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWalletsTableAddWalletEnabled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet', function (Blueprint $table) {

            $table->tinyInteger('wallet_enabled')
                ->default(1)
                ->after('amount_inorder');
            $table->string('coin_type')
                ->nullable()
            ->after('wallet_enabled');
            $table->dropUnique('wallet_address_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet', function (Blueprint $table) {

            $table->dropColumn('wallet_enabled');
            $table->dropColumn('coin_type');
        });
    }
}
