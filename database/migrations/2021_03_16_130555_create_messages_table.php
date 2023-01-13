<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('seller_id')->nullable();
            $table->bigInteger('buyer_id')->nullable();
            $table->integer('thread_id')->nullable()->comment('message thread id');
            $table->text('message')->nullable();
            $table->boolean('is_private')->default(0)->comment('1=yes;0=not');
            $table->boolean('is_read')->default(0)->comment('1=yes;0=not read');
            $table->boolean('is_admin')->default(0)->comment('1=Admin;0=not admin');
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
        Schema::dropIfExists('messages');
    }
}
