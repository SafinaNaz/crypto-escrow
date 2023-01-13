<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesColumnsIndexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escrow_products', function (Blueprint $table) {
            $table->index(['seller_id', 'buyer_id', 'currency_id']);
        });

        Schema::table('message_threads', function (Blueprint $table) {
            $table->index(['seller_id', 'buyer_id', 'product_id']);
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['sender_id', 'receiver_id', 'thread_id']);
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['seller_id', 'buyer_id', 'product_id']);
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['currency_id', 'product_id', 'status_id']);
        });
        Schema::table('requested_pocs', function (Blueprint $table) {
            $table->index(['product_id']);
        });
    }
}
