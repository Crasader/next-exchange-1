<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTypeOrdersTransations extends Migration
{
    private $_tables = [
        'orders',
        'transactions',
    ];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->_tables as $table) {
            DB::unprepared('ALTER TABLE ' . $table . ' ENGINE = InnoDB');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        foreach ($this->_tables as $table) {
            DB::unprepared('ALTER TABLE ' . $table . ' ENGINE = MyISAM');
        }
    }
}
