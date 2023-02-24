<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableBadasoPermissionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table(config('badaso.database.prefix').'permissions', function (Blueprint $table) {
                $table->string('roles_can_see_all_data')->after('is_public')->nullable();
                $table->string('field_identify_related_user')->after('roles_can_see_all_data')->nullable();
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
        //
    }
}
