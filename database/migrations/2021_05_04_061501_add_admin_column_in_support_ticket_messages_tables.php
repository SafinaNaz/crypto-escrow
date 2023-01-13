<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminColumnInSupportTicketMessagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->boolean('is_admin')->default(0)->comment('1=admin;0=user')->after('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            //
        });
    }
}
