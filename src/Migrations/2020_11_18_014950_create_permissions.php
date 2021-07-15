<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create(config('badaso.database.prefix').'permissions', function (Blueprint $table) {
                $table->id();
                $table->string('key')->index();
                $table->string('description')->nullable();
                $table->string('table_name')->nullable();
                $table->boolean('always_allow')->default(false);
                $table->boolean('is_public')->default(false);
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
        Schema::dropIfExists(config('badaso.database.prefix').'permissions');
    }
}
