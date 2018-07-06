<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPasswordSecuritiesModifyGoogle2faSecret extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_securities', function (Blueprint $table) {
            $table->string('google2fa_secret', 220)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_securities', function (Blueprint $table) {
            $table->string('google2fa_secret')->nullable()->change();
        });
    }
}
