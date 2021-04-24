<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirebaseServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firebase_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_key')->nullable();
            $table->string('auth_domain')->nullable();
            $table->string('project_id')->nullable();
            $table->string('storage_bucket')->nullable();
            $table->string('messaging_sender_id')->nullable();
            $table->string('app_id')->nullable();
            $table->string('measurement_id')->nullable();
            $table->string('server_key')->nullable();
            $table->boolean('active_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firebase_services');
    }
}
