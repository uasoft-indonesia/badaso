<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIsSoftDeleteInDataTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_types', function (Blueprint $table) {
            if (! Schema::hasColumn('data_types', 'is_soft_delete')) {
                $table->boolean('is_soft_delete')->after('notification')->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_types', function (Blueprint $table) {
            if (Schema::hasColumn('data_types', 'is_soft_delete')) {
                $table->dropColumn('is_soft_delete');
            }
        });
    }
}
