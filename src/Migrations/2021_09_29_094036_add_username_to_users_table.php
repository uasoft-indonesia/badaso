<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsernameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('badaso.database.prefix').'users', function (Blueprint $table) {
            if (! Schema::hasColumn(config('badaso.database.prefix').'users', 'username')) {
                $table->string('username')->unique()->nullable()->default(null)->after('name');
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
        Schema::table(config('badaso.database.prefix').'users', function (Blueprint $table) {
            if (Schema::hasColumn(config('badaso.database.prefix').'users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }
}
