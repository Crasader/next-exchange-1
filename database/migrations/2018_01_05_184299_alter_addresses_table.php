<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddressesTable extends Migration
{
    /**
     * This will put some security features into the table
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {

            $table->binary('salt')
                ->nullable()
                ->after('pk');
            $table->binary('IV')
                ->nullable()
                ->after('salt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {

            $table->dropColumn('salt');
            $table->dropColumn('IV');
        });
    }
}
