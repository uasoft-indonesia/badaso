<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenameFCMMessagesTableToNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename(config('badaso.database.prefix').'f_c_m_messages', config('badaso.database.prefix').'notifications');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename(config('badaso.database.prefix').'notifications', config('badaso.database.prefix').'f_c_m_messages');
    }
}
