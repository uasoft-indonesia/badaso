<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create(config('badaso.database.prefix').'data_types', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('display_name_singular');
                $table->string('display_name_plural');
                $table->string('icon')->nullable();
                $table->string('model_name')->nullable();
                $table->string('policy_name')->nullable();
                $table->string('controller')->nullable();
                $table->string('order_column')->nullable();
                $table->string('order_display_column')->nullable();
                $table->enum('order_direction', ['ASC', 'DESC'])->nullable();
                $table->boolean('generate_permissions')->default(false);
                $table->tinyInteger('server_side')->default(0);
                $table->boolean('is_maintenance')->default(false);
                $table->text('description')->nullable();
                $table->text('details')->nullable();
                $table->text('notification')->nullable();
                $table->boolean('is_soft_delete')->default(0)->nullable();
                $table->timestamps();
            });

            // Create table for storing roles
            Schema::create(config('badaso.database.prefix').'data_rows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('data_type_id');
                $table->string('field');
                $table->string('type');
                $table->string('display_name');
                $table->boolean('required')->default(false);
                $table->boolean('browse')->default(true);
                $table->boolean('read')->default(true);
                $table->boolean('edit')->default(true);
                $table->boolean('add')->default(true);
                $table->boolean('delete')->default(true);
                $table->text('details')->nullable();
                $table->text('relation')->nullable();
                $table->integer('order')->default(1);

                $table->foreign('data_type_id')->references('id')->on(config('badaso.database.prefix').'data_types')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists(config('badaso.database.prefix').'data_rows');
        Schema::dropIfExists(config('badaso.database.prefix').'data_types');
    }
}
