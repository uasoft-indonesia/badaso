<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            if (!Schema::hasColumn('data_types', 'is_soft_delete')) {
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
