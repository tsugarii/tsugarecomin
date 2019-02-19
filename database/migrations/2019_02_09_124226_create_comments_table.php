<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('rank');
            $table->string('board_key');
            $table->string('thread_key');
            $table->string('comment_key');
            $table->integer('res_number');
            $table->string('name');
            $table->string('command');
            $table->string('time');
            $table->integer('epoch_time');
            $table->string('id');
            $table->string('user_id');
            $table->timestamps();
            $table->string('ip');
            $table->text('message');
            $table->integer('like');
            $table->integer('delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
