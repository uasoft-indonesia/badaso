<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAvatarFieldOnUserTableAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('additional_info')->default('files/shares/default-user.png')->change();
                }
            });
        } catch (PDOException $ex) {
            $this->down();

            throw $ex;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('additional_info')->default('/files/shares/default-user.png')->change();
            }
        });
    }
}
