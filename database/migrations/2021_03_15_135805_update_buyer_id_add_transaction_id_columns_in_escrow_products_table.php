<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBuyerIdAddTransactionIdColumnsInEscrowProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escrow_products', function (Blueprint $table) {
            $table->bigInteger('buyer_id')->nullable()->comment('buyer_id')->after('user_id')->change();
            $table->renameColumn('user_id', 'seller_id')->nullable();
            $table->string('transaction_id',100)->nullable()->after('buyer_id');
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
