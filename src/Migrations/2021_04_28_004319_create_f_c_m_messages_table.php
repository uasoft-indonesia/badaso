<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFCMMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('badaso.database.prefix').'f_c_m_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('receiver_user_id');
            $table->string('type');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_read')->default(0);
            $table->unsignedBigInteger('sender_user_id');
            $table->timestamps();

            $table->foreign('receiver_user_id')->references('id')->on(config('badaso.database.prefix').'users')->onDelete('cascade');
            $table->foreign('sender_user_id')->references('id')->on(config('badaso.database.prefix').'users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('badaso.database.prefix').'f_c_m_messages');
    }
}
