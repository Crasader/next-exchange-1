<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPasswordSecurities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('password_securities');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('password_securities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->boolean('google2fa_enable')->default(false);
            $table->string('google2fa_secret')->nullable();
            $table->boolean('authy2fa_enable')->default(false);
            $table->string('authy2fa_secret')->nullable();
            $table->string('two_factor_options')->nullable();
            $table->timestamps();
        });
    }
}
