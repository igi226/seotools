<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function ($table) {
            $table->renameColumn('footer', 'visibility');
        });

        foreach (DB::table('plans')->select('*')->cursor() as $row) {
            $features = json_decode($row->features);

            DB::statement("UPDATE `plans` SET `features` = :features WHERE `id` = :id", ['features' => json_encode(['reports' => $features->reports, 'branded_reports' => 1, 'white_label_reports' => 1, 'research_tools' => 1, 'developer_tools' => 1, 'content_tools' => 1, 'data_export' => 1, 'api' => 1]), 'id' => $row->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
