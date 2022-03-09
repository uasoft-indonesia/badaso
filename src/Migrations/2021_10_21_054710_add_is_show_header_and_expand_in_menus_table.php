<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsShowHeaderAndExpandInMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('badaso.database.prefix').'menus', function (Blueprint $table) {
            if (! Schema::hasColumn(config('badaso.database.prefix').'menus', 'is_show_header')) {
                $table->boolean('is_show_header')->default(true)->after('icon');
            }
            if (! Schema::hasColumn(config('badaso.database.prefix').'menus', 'is_expand')) {
                $table->boolean('is_expand')->default(true)->after('icon');
            }
            if (! Schema::hasColumn(config('badaso.database.prefix').'menus', 'order')) {
                $table->boolean('order')->nullable()->after('icon');
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
        Schema::table(config('badaso.database.prefix').'menus', function (Blueprint $table) {
            if (Schema::hasColumn(config('badaso.database.prefix').'menus', 'is_show_header')) {
                $table->dropColumn('is_show_header');
            }
            if (Schema::hasColumn(config('badaso.database.prefix').'menus', 'is_expand')) {
                $table->dropColumn('is_expand');
            }
            if (Schema::hasColumn(config('badaso.database.prefix').'menus', 'order')) {
                $table->dropColumn('order');
            }
        });
    }
}
