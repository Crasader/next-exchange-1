<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('address_id');
            $table->unsignedInteger('address_user_id');
            $table->string('address_address',256);
            $table->string('address_coin',128);
            $table->string('address_name',50);
            $table->string('address_type',50);
            $table->binary('pk');
            $table->timestamps();

            $table->foreign('address_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(array('address_address', 'address_coin'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
