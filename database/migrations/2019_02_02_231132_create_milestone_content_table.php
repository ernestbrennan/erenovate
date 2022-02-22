<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestoneContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_milestone_content', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('milestone_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->float('price', 12, 2)->nullable();
            $table->string('status');
            $table->string('version');
            $table->string('materials_provide_on');
            $table->string('material_supply_side');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

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
        Schema::dropIfExists('guarantee_milestone_content');
    }
}
