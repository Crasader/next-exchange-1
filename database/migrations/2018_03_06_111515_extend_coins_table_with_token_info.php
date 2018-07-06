<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendCoinsTableWithTokenInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->tinyInteger('coin_erc')->after('coin_market')->nullable()->default(0);
            $table->string('coin_address')->after('coin_erc')->nullable()->unique();
            $table->string('coin_exchanger')->after('coin_address')->nullable();
            $table->tinyInteger('coin_network')->after('coin_exchanger')->nullable()->default(1);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->dropColumn('coin_erc');
            $table->dropColumn('coin_address');
            $table->dropColumn('coin_exchanger');
            $table->dropColumn('coin_network');
            $table->dropColumn('deleted_at');
        });
    }
}
