<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEscrowColumnsInSitesettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->float('escrow_fee_btc')->default(0)->comment('btc escrow fee in percentage')->after('site_address');
            $table->float('escrow_fee_monero')->default(0)->comment('modero escrow fee in percentage')->after('escrow_fee_btc');
            $table->string('btc_address')->nullable()->after('escrow_fee_monero');
            $table->string('monero_address')->nullable()->after('btc_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            //
        });
    }
}
