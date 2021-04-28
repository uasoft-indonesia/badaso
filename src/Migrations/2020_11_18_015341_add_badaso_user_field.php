<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBadasoUserField extends Migration
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
                if (! Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('email')->default('users/default.png');
                }
                if (! Schema::hasColumn('users', 'additional_info')) {
                    $table->text('additional_info')->nullable()->after('email');
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
        if (Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('avatar');
            });
        }
        if (Schema::hasColumn('users', 'additional_info')) {
            Schema::table('users', function ($table) {
                $table->dropColumn('additional_info');
            });
        }
    }
}
