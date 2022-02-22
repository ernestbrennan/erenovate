<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_issue', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('guarantee_project_id');
            $table->integer('chat_id');
            $table->string('title');
            $table->text('description');
            $table->smallInteger('sequence');
            $table->string('status');
            $table->string('type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantee_issue');
    }
}
