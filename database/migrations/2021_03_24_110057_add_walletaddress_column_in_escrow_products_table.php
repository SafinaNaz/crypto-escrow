<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletaddressColumnInEscrowProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escrow_products', function (Blueprint $table) {
            $table->string('wallet_address')->nullable()->after('escrow_fee_payer')->comment('admin wallet address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escrow_products', function (Blueprint $table) {
            //
        });
    }
}
