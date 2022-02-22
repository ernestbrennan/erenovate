<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToGuaranteeProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('guarantee_project', function (Blueprint $table) {
            $table->integer('homeowner_id')->after('contractor_id');
            $table->smallInteger('contractor_visited')->after('homeowner_id');
            $table->smallInteger('homeowner_visited')->after('contractor_visited');
        });

        \App\Models\GuaranteeProject::all()->map(function($item){
            $item->homeowner_id = $item->project['user_id'];
            $item->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guarantee_project', function (Blueprint $table) {
            $table->dropColumn('homeowner_id');
            $table->dropColumn('contractor_visited');
            $table->dropColumn('homeowner_visited');
        });
    }
}
