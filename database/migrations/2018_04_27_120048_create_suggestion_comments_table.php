<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuggestionCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('suggestion_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body');
            $table->integer('user_id');
            $table->integer('suggestion_id');
            $table->timestamps();

            $table->unique(['user_id', 'suggestion_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suggestion_comments');
    }
}
