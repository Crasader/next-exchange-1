<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE suggestions MODIFY COLUMN status ENUM('done', 'planned','not-planned','under-consideration','pending')");
        DB::statement("ALTER TABLE suggestion_votes ADD UNIQUE votes_index (user_id, suggestion_id);");
        DB::statement("ALTER TABLE suggestion_comments ADD UNIQUE votes_index (user_id, suggestion_id);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP INDEX votes_index ON suggestion_votes");
        DB::statement("DROP INDEX votes_index ON suggestion_comments");
    }
}
