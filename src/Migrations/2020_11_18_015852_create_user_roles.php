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
            Schema::create(config('badaso.database.prefix').'user_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id');
                $table->foreignId('role_id');
                $table->foreign('user_id')->references('id')->on(config('badaso.database.prefix').'users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on(config('badaso.database.prefix').'roles')->onDelete('cascade');
                $table->timestamps();
            });
        } catch (PDOException $ex) {
            $this->down();

            try {
                Schema::create(config('badaso.database.prefix').'user_roles', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id');
                    $table->foreignId('role_id');
                    $table->foreign('user_id')->references('id')->on(config('badaso.database.prefix').'users')->onDelete('cascade');
                    $table->foreign('role_id')->references('id')->on(config('badaso.database.prefix').'roles')->onDelete('cascade');
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
        Schema::dropIfExists(config('badaso.database.prefix').'user_roles');
    }
}
