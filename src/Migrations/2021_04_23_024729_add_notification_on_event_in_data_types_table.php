<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationOnEventInDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_types', function (Blueprint $table) {
            if (! Schema::hasColumn('data_types', 'notification')) {
                $table->text('notification')->nullable()->after('details');
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
            if (Schema::hasColumn('data_types', 'notification')) {
                $table->dropColumn('notification');
            }
        });
    }
}
