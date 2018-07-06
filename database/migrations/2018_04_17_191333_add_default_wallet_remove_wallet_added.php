<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultWalletRemoveWalletAdded extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('wallet_added');
        });

        Schema::table('coins', function (Blueprint $table) {

            $table->tinyInteger('default_wallet')
                ->default(0)
                ->after('wallet_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            /**
             * Field to check the default wallets added or not
             */
            $table->tinyInteger('wallet_added')
                ->default(0)
                ->after('user_disclaimer');
        });

        Schema::table('coins', function (Blueprint $table) {

            $table->dropColumn('default_wallet');
        });
    }
}
