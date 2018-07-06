<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcosRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icos_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ico_id')->index();
            $table->string('name');
            $table->string('display_name');
            $table->timestamps();

            $table->foreign('ico_id')->references('id')->on('ico')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icos_roles');
    }
}
