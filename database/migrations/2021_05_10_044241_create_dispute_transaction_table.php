<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisputeTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispute_transaction', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->integer('message_id')->nullable();
            $table->foreign('message_id', 'dispute_transaction_ messages_ibfk_1')->references('id')->on('messages')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->float('discount_offer')->default(0)->comment('seller / buyer offers');
            $table->boolean('level')->default(1);
            $table->dateTime('offer_expire_time')->nullable();
            $table->boolean('status')->default(0)->comment('dispute status as accepted or rejected 1,2 rejected');    

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
        Schema::dropIfExists('dispute_transaction');
    }
}
