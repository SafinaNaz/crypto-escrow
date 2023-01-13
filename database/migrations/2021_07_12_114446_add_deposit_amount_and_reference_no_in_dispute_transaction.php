<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepositAmountAndReferenceNoInDisputeTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispute_transaction', function (Blueprint $table) {
            $table->string('reference_no')->nullable()->comment('admin payment ref number');
            $table->integer('deposit_amount')->default(0)->comment('deposit amount for admin to get level 3');
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
