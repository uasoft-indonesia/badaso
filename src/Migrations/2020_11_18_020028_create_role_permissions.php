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
            Schema::create(config('badaso.database.prefix').'role_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id');
                $table->foreignId('permission_id');
                $table->foreign('role_id')->references('id')->on(config('badaso.database.prefix').'roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on(config('badaso.database.prefix').'permissions')->onDelete('cascade');
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
        Schema::dropIfExists(config('badaso.database.prefix').'role_permissions');
    }
}
