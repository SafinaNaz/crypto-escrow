<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscrowProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('escrow_products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('user_id')->nullable();
            $table->string('buying_selling_option',20)->default('seller');
            $table->string('product_name',255)->nullable();
            $table->string('buyer_seller_email',50)->nullable();
            $table->boolean('currency')->default(1)->comment('currency 1=bitcoin , 2=monero');
            $table->float('price')->default(0)->comment('product price');
            $table->float('total_price')->default(0)->comment('product price with escrow commission');
            $table->float('commission')->default(0)->comment('escrow commission');
            $table->text('encrypted_text')->nullable();
            $table->text('non_encrypted_text')->nullable();
            $table->boolean('escrow_fee_payer')->default(1)->comment('1=buyer;2=seller;3=50 50');
            $table->string('completion_time')->nullable()->comment('number of days');
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
        Schema::dropIfExists('escrow_products');
    }
}
