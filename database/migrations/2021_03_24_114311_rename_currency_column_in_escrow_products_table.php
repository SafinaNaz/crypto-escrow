<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCurrencyColumnInEscrowProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escrow_products', function (Blueprint $table) {
            $table->integer('currency')->nullable()->change();
            $table->renameColumn('currency', 'currency_id');
            
        });
    }
}
