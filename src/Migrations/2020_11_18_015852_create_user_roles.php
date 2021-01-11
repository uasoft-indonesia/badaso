<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('user_roles', function (Blueprint $table) {
                // $table->unsignedInteger('user_id')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedInteger('role_id')->index();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->primary(['user_id', 'role_id']);
                $table->timestamps();
            });
        } catch (PDOException $ex) {
            $this->down();
            try {
                Schema::create('user_roles', function (Blueprint $table) {
                    $table->unsignedInteger('user_id')->index();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->unsignedInteger('role_id')->index();
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                    $table->primary(['user_id', 'role_id']);
                    $table->timestamps();
                });
            } catch (PDOException $ex) {
                $this->down();
                throw $ex;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
