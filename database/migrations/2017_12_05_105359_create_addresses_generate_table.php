<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesGenerateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses_generate', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coin_market');
            $table->string('address')->unique();
            $table->tinyInteger('expired')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('coin_market')->references('coin_id')->on('coins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses_generate');
    }
}
