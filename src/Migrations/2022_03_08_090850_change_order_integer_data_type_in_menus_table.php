<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrderIntegerDataTypeInMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn(config('badaso.database.prefix').'menus', 'order')) {
                $table->integer('order')->change();
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
        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn(config('badaso.database.prefix').'menus', 'order')) {
                $table->integer('order')->change();
            }
        });
    }
}
