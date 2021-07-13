<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create(config('badaso.database.prefix') . 'role_permissions', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('role_id')->unsigned();
                $table->foreign('role_id')->references('id')->on(config('badaso.database.prefix') .'roles')->onDelete('cascade');
                $table->unsignedInteger('permission_id')->unsigned();
                $table->foreign('permission_id')->references('id')->on(config('badaso.database.prefix') .'permissions')->onDelete('cascade');
                $table->timestamps();
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
        Schema::dropIfExists(config('badaso.database.prefix') . 'role_permissions');
    }
}
