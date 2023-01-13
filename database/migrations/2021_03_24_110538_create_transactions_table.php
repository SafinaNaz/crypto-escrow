<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id');
            $table->integer('currency_id');
            $table->string('reference_no')->nullable()->comment('buyer payment ref number');
            $table->float('total_amount')->default(0)->comment('product amount with escrow commission');
            $table->float('commission')->default(0)->comment('calculated escrow commission');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
