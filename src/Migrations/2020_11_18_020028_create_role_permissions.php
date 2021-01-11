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
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->unsignedInteger('role_id')->unsigned();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->unsignedInteger('permission_id')->unsigned();
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->timestamps();
                $table->primary(['role_id', 'permission_id']);
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
        Schema::dropIfExists('role_permissions');
    }
}
