<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinFeeWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_fee_wallet', function (Blueprint $table) {

            $table->increments('id');
            $table->string('tx_id')->unique();
            $table->unsignedInteger('coin_id');
            $table->string('name')->nulled();
            $table->decimal('amount',20, 9)->default(0); // 99B max
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coin_id')->references('coin_id')->on('coins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_fee_wallet');
    }
}
