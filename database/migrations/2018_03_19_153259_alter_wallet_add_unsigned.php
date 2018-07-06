<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWalletAddUnsigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet', function (Blueprint $table) {

            $table->decimal('amount', 20, 9)
                ->default(0)
                ->unsigned()
                ->change();

            $table->decimal('amount_inorder', 20, 9)
                ->unsigned()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet', function (Blueprint $table) {

            $table->decimal('amount', 20, 9)
                ->unsigned(false)
                ->change();

            $table->decimal('amount_inorder', 20, 9)
                ->unsigned(false)
                ->change();
        });
    }
}
