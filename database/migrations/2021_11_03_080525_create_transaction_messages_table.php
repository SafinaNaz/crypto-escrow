<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_messages', function (Blueprint $table) {
           $table->integer('id', true);
           $table->bigInteger('seller_id')->nullable();
           $table->bigInteger('buyer_id')->nullable();
           $table->bigInteger('admin_id')->nullable();
           $table->integer('product_id')->nullable()->comment('escrow product id');
           $table->boolean('transaction_type')->default(0)->comment('0 for escrow transactions, 1 for dispute_transaction, 2 for request pocs');
           $table->boolean('product_status')->default(0);
           $table->text('message')->nullable();
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
        Schema::dropIfExists('transaction_messages');
    }
}
