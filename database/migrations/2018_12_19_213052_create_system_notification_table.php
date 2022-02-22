<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_system_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id');
            $table->integer('contract_id')->nullable();
            $table->integer('contract_draft_id')->nullable();
            $table->integer('milestone_id')->nullable();
            $table->integer('milestone_content_id')->nullable();
            $table->integer('materials_content_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('issue_id')->nullable();
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
        Schema::dropIfExists('guarantee_system_notification');
    }
}
