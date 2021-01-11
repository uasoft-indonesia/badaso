<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('menu_id')->nullable();
                $table->string('title');
                $table->string('url');
                $table->string('target')->default('_self');
                $table->string('icon_class')->nullable();
                $table->string('color')->nullable();
                $table->integer('parent_id')->nullable();
                $table->integer('order');
                $table->text('permissions')->nullable();
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
        Schema::dropIfExists('menu_items');
    }
}
