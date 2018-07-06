<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketCapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_caps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cmc_id', 32)->nullable();
            $table->string('name', 32)->nullable();
            $table->string('symbol', 16)->nullable();
            $table->integer('rank')->nullable();
            $table->string('price_usd', 64)->nullable();
            $table->string('price_btc', 64)->nullable();
            $table->string('24h_volume_usd', 64)->nullable();
            $table->string('market_cap_usd', 64)->nullable();
            $table->string('available_supply', 64)->nullable();
            $table->string('total_supply', 64)->nullable();
            $table->string('max_supply', 64)->nullable();
            $table->string('percent_change_1h', 64)->nullable();
            $table->string('percent_change_24h', 64)->nullable();
            $table->string('percent_change_7d', 64)->nullable();
            $table->string('last_updated', 64)->nullable();
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
        Schema::dropIfExists('market_caps');
    }
}
