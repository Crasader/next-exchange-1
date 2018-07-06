<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('fullname', 128)->nullable();
            $table->string('slug')->index()->nullable();
            $table->string('occupation', 128)->nullable();
            $table->string('company', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('address', 128)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('state', 32)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('country', 32)->nullable();
            $table->string('bitcoin', 64)->nullable();
            $table->string('ether', 64)->nullable();
            $table->string('litecoin', 64)->nullable();
            $table->string('facebook', 64)->nullable();
            $table->string('linkedin', 64)->nullable();
            $table->string('twitter', 64)->nullable();
            $table->string('instagram', 64)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_details');
    }
}
