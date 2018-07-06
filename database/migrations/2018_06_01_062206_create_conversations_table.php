<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Conversation;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('creator_id')->index()->nullable();
            $table->string('image')->nullable();
            $table->string('name')->index();
            $table->boolean('is_global')->default(false);
            $table->enum('type', [Conversation::ONE_WAY, Conversation::TWO_WAY])
                ->default(Conversation::TWO_WAY);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coversations');
    }
}
