<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ico', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('symbol')->nullable();
            $table->string('total_supply_token')->nullable();
            $table->string('stage')->nullable();
            $table->date('launch_date')->nullable();
            $table->decimal('initial_price', 10, 8)->nullable();
            $table->string('short_description')->nullable();
            $table->string('full_description')->nullable();
            $table->string('website_url')->nullable();
            $table->string('whitepaper_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('telegram_url')->nullable();
            $table->string('bitcointalk_url')->nullable();
            $table->string('official_video_url')->nullable();
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
        Schema::dropIfExists('ico');
    }
}
