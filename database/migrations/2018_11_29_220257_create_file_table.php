<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantee_file', function (Blueprint $table) {

            $table->increments('id');
            $table->string('extension');
            $table->string('name');
            $table->string('hash_name');
            $table->string('path');
            $table->string('url');
            $table->string('size');

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
        Schema::dropIfExists('guarantee_file');
    }
}
