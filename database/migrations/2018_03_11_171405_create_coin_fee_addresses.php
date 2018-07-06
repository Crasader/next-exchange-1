<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinFeeAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_fee_addresses', function (Blueprint $table) {

            $table->increments('id');
            $table->string('coin_fee_address',256);
            $table->string('coin_fee_coin',128);
            $table->string('address_name',50);
            $table->binary('pk');
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
        Schema::dropIfExists('coin_fee_addresses');
    }
}
