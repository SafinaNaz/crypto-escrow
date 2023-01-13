<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForigenKeyInSupportTicketMessagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->bigInteger('ticket_id')->unsigned()->nullable()->change();
            $table->foreign('ticket_id','support_ticket_messages_ibfk_1')->references('id')->on('support_tickets')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
            $table->dropForeign('support_ticket_messages_ibfk_1');
        });
    }
}
