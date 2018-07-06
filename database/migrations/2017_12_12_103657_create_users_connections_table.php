<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_connections', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('connected_user_id')->index();
            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->timestamps();

            $table->primary(['user_id', 'connected_user_id']);

            $table->foreign(['user_id'])->references('id')->on('users')->onDelete('cascade');
            $table->foreign(['connected_user_id'])->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_connections');
    }
}
