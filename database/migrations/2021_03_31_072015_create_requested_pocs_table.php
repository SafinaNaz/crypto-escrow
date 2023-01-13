<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestedPocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_pocs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id');
            $table->string('reference_no')->nullable()->comment('buyer payment ref number');
            $table->string('admin_reference_no')->nullable()->comment('admin ref number for seller transfer');
            $table->float('poc_amount')->default(0)->comment('poc amount total');
            $table->float('poc_percentage')->default(0);
            $table->boolean('status')->default(0)->comment('0=requested;1=responded');
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
        Schema::dropIfExists('requested_pocs');
    }
}
