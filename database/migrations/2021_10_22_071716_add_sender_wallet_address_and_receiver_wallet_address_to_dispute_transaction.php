<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSenderWalletAddressAndReceiverWalletAddressToDisputeTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispute_transaction', function (Blueprint $table) {
            $table->text('sender_wallet_address')->nullable();
            $table->text('receiver_wallet_address')->nullable();
            $table->text('admin_sender_wallet_address')->nullable();
            $table->text('admin_receiver_wallet_address')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispute_transaction', function (Blueprint $table) {
          
        });
    }
}
