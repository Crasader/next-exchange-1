<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddressesAddPaymentidStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {

            $table->string('address_payment_id')
                ->after('address_address')
                ->default(0);

            $table->string('address_payment_id_type')
                ->after('address_payment_id')
                ->nullable(true);
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

            $table->dropColumn('address_payment_id');
            $table->dropColumn('address_payment_id_type');
        });
    }
}
