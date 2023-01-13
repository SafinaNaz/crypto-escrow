<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllTableForigenKeysInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_threads', function (Blueprint $table) {
            $table->foreign('product_id', 'message_threads_ibfk_1')->references('id')->on('escrow_products')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('product_id', 'transactions_ibfk_1')->references('id')->on('escrow_products')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });

        Schema::table('requested_pocs', function (Blueprint $table) {
            $table->foreign('product_id', 'requested_pocs_ibfk_1')->references('id')->on('escrow_products')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('message_threads_ibfk_1');
            $table->dropForeign('transactions_ibfk_1');
            $table->dropForeign('requested_pocs_ibfk_1');
        });
    }
}
