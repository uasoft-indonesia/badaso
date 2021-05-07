<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMaintenanceToDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_types', function (Blueprint $table) {
            if (!Schema::hasColumn('data_types', 'is_maintenance')) {
                $table->boolean('is_maintenance')->default(false)->after('server_side');
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
            if (Schema::hasColumn('data_types', 'is_maintenance')) {
                $table->dropColumn('is_maintenance');
            }
        });
    }
}
