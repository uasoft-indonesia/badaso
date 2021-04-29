<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFCMMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_c_m_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receiver_user_id');
            $table->string('type');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_read')->default(0);
            $table->unsignedBigInteger('sender_user_id');
            $table->timestamps();

            $table->foreign('receiver_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sender_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('f_c_m_messages');
    }
}
