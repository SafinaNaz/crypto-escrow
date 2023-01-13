<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSenderWalletAddressAndReceiverWalletAddressToRequestedPocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_pocs', function (Blueprint $table) {
            $table->text('sender_wallet_address')->nullable();
            $table->text('receiver_wallet_address')->nullable();
            
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
        Schema::table('requested_pocs', function (Blueprint $table) {
            //
        });
    }
}
