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
        Schema::table('badaso_menus', function (Blueprint $table) {
            if (!Schema::hasColumn('badaso_menus', 'is_show_header')) {
                $table->boolean('is_show_header')->default(true);
            }
            if (!Schema::hasColumn('badaso_menus', 'is_expand')) {
                $table->boolean('is_expand')->default(true);
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
        Schema::table('badaso_menus', function (Blueprint $table) {
            if (Schema::hasColumn('badaso_menus', 'is_show_header')) {
                $table->dropColumn('is_show_header');
            }
            if (Schema::hasColumn('badaso_menus', 'is_expand')) {
                $table->dropColumn('is_expand');
            }
        });
    }
}
