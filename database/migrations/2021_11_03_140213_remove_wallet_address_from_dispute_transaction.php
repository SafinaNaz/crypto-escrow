<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveWalletAddressFromDisputeTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispute_transaction', function (Blueprint $table) {
            $table->dropColumn('sender_wallet_address');
            $table->dropColumn('receiver_wallet_address');
            $table->dropColumn('admin_sender_wallet_address');
            $table->dropColumn('admin_receiver_wallet_address');
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
            //
        });
    }
}
