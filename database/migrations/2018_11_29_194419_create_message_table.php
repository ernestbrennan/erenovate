<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_message', function (Blueprint $table) {

            $table->increments('id');
            $table->string('type');
            $table->integer('chat_id');
            $table->integer('sender_id');
            $table->text('content');
            $table->smallInteger('receiver_seen')->default(\App\Models\Message::RECEIVER_NOT_SEEN);

            $table->timestamp('created_at');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantee_message');
    }
}
