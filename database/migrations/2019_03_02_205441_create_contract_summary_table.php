<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractSummaryTable extends Migration
{

    public function up()
    {
        Schema::create('guarantee_contract_draft_summary', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('contract_draft_id');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->float('service_cost', 12, 2)->nullable();
            $table->float('material_cost', 12, 2)->nullable();
            $table->float('changed_total', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guarantee_contract_draft_summary');
    }
}
